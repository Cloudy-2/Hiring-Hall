<?php

namespace App\Console\Commands;

use App\Models\Chats\Conversation;
use App\Services\Chat\ModerationBotService;
use Illuminate\Console\Command;

class CreateRulesChannels extends Command
{
    protected $signature = 'chat:create-rules-channels';

    protected $description = 'Create #rules channel for all existing group conversations';

    public function handle()
    {
        $groups = Conversation::where('type', 'group')->get();

        $created = 0;
        $skipped = 0;

        foreach ($groups as $group) {
            $topic = ModerationBotService::createRulesChannel($group);

            if ($topic->wasRecentlyCreated) {
                $created++;
                $this->info("Created #rules for group: {$group->name} (ID: {$group->id})");
            } else {
                $skipped++;
            }
        }

        $this->info("Done! Created: {$created}, Skipped (already exists): {$skipped}");

        return Command::SUCCESS;
    }
}
