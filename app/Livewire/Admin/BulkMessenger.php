<?php

namespace App\Livewire\Admin;

use App\Contracts\SmsGatewayInterface;
use App\Mail\BulkAnnouncementMail;
use App\Models\BulkMessage;
use App\Models\ClassLevel;
use App\Models\Group;
use App\Models\Role;
use App\Models\Season;
use App\Models\Student;
use App\Models\User;
use App\Traits\AuthorizesWriteAction;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class BulkMessenger extends Component
{
    use AuthorizesWriteAction;

    public $audienceType = 'students'; // students | team
    public $filterGroupId = '';
    public $filterClassId = '';
    public $filterSeasonId = '';
    public $filterRole = '';

    public $channel = 'email'; // email | sms
    public $subject = '';
    public $body = '';

    protected function rules(): array
    {
        return [
            'channel' => 'required|in:email,sms',
            'subject' => $this->channel === 'email' ? 'required|string|max:255' : 'nullable|string|max:255',
            'body'    => 'required|string|max:2000',
        ];
    }

    protected function recipients()
    {
        if ($this->audienceType === 'team') {
            $query = User::where('role', '!=', 'student')
                ->where(fn ($q) => $q->where('role', 'admin')->orWhereHas('team', fn ($t) => $t->where('status', 1)));

            if ($this->filterRole) {
                $query->where('role', $this->filterRole);
            }

            return $query->get(['id', 'name', 'email', 'phone']);
        }

        $query = Student::query();

        if ($this->filterGroupId) {
            $query->where('group_id', $this->filterGroupId);
        }
        if ($this->filterClassId) {
            $query->where('class_id', $this->filterClassId);
        }
        if ($this->filterSeasonId) {
            $query->where('season_id', $this->filterSeasonId);
        }

        return $query->get(['id', 'name', 'email', 'phone']);
    }

    public function recipientCount()
    {
        return $this->recipients()->count();
    }

    public function saveDraft()
    {
        if (!$this->requireWrite('bulk-messages-send')) return;

        $this->validate();

        BulkMessage::create([
            'channel'          => $this->channel,
            'subject'          => $this->subject,
            'body'             => $this->body,
            'status'           => 'draft',
            'audience_filters' => $this->currentFilters(),
            'total_recipients' => $this->recipientCount(),
            'created_by'       => auth()->id(),
        ]);

        session()->flash('success', 'Draft saved.');
        $this->reset('subject', 'body');
    }

    public function loadDraft($id)
    {
        $draft = BulkMessage::where('status', 'draft')->findOrFail($id);

        $this->channel = $draft->channel;
        $this->subject = $draft->subject ?? '';
        $this->body = $draft->body;

        $filters = $draft->audience_filters ?? [];
        $this->audienceType = $filters['audienceType'] ?? 'students';
        $this->filterGroupId = $filters['filterGroupId'] ?? '';
        $this->filterClassId = $filters['filterClassId'] ?? '';
        $this->filterSeasonId = $filters['filterSeasonId'] ?? '';
        $this->filterRole = $filters['filterRole'] ?? '';
    }

    public function deleteDraft($id)
    {
        if (!$this->requireWrite('bulk-messages-send')) return;

        BulkMessage::where('status', 'draft')->findOrFail($id)->delete();
        session()->flash('success', 'Draft deleted.');
    }

    public function send()
    {
        if (!$this->requireWrite('bulk-messages-send')) return;

        $this->validate();

        $recipients = $this->recipients();
        $sent = 0;
        $failed = 0;

        foreach ($recipients as $recipient) {
            try {
                if ($this->channel === 'email') {
                    if (!$recipient->email) {
                        $failed++;
                        continue;
                    }
                    Mail::to($recipient->email)->send(new BulkAnnouncementMail($this->subject, $this->body));
                } else {
                    if (!$recipient->phone) {
                        $failed++;
                        continue;
                    }
                    app(SmsGatewayInterface::class)->send($recipient->phone, $this->body);
                }
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        BulkMessage::create([
            'channel'          => $this->channel,
            'subject'          => $this->subject,
            'body'             => $this->body,
            'status'           => 'sent',
            'audience_filters' => $this->currentFilters(),
            'total_recipients' => $recipients->count(),
            'sent_count'       => $sent,
            'failed_count'     => $failed,
            'created_by'       => auth()->id(),
        ]);

        session()->flash('success', "Sent to {$sent} of {$recipients->count()} recipients ({$failed} failed).");
        $this->reset('subject', 'body');
    }

    protected function currentFilters(): array
    {
        return [
            'audienceType'   => $this->audienceType,
            'filterGroupId'  => $this->filterGroupId,
            'filterClassId'  => $this->filterClassId,
            'filterSeasonId' => $this->filterSeasonId,
            'filterRole'     => $this->filterRole,
        ];
    }

    public function render()
    {
        return view('livewire.admin.bulk-messenger', [
            'groups'      => Group::orderBy('name')->get(),
            'classLevels' => ClassLevel::orderBy('name')->get(),
            'seasons'     => Season::orderBy('name')->get(),
            'roles'       => Role::teamRoles(),
            'recipientCount' => $this->recipientCount(),
            'drafts'      => BulkMessage::where('status', 'draft')->latest()->take(10)->get(),
            'history'     => BulkMessage::where('status', 'sent')->latest()->take(10)->get(),
        ]);
    }
}
