<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WebhookController extends Controller
{
    public function handleGithubWebhook(Request $request)
    {
        $payload = $request->all();
        $event = $request->header('X-GitHub-Event');
        
        $update = [
            'type' => $this->getEventType($event),
            'message' => $this->getMessage($payload, $event),
            'url' => $this->getUrl($payload, $event),
            'created_at' => now()->toIso8601String()
        ];
        
        Redis::zadd('github_updates', now()->timestamp, json_encode($update));
        
        return response()->json(['status' => 'success']);
    }
    
    private function getEventType($event)
    {
        return match($event) {
            'push' => 'Push Event',
            'pull_request' => 'Pull Request',
            'issues' => 'Issue Update',
            default => 'Repository Update'
        };
    }
    
    private function getMessage($payload, $event)
    {
        return match($event) {
            'push' => $payload['head_commit']['message'] ?? 'New push to repository',
            'pull_request' => $payload['pull_request']['title'] ?? 'Pull request update',
            'issues' => $payload['issue']['title'] ?? 'Issue update',
            default => 'Repository updated'
        };
    }
    
    private function getUrl($payload, $event)
    {
        return match($event) {
            'push' => $payload['head_commit']['url'] ?? $payload['repository']['html_url'],
            'pull_request' => $payload['pull_request']['html_url'] ?? $payload['repository']['html_url'],
            'issues' => $payload['issue']['html_url'] ?? $payload['repository']['html_url'],
            default => $payload['repository']['html_url']
        };
    }
}
