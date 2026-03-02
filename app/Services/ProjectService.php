<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Activity;

class ProjectService
{
    public function create(array $data): Project
    {
        $project = Project::create($data);

        Activity::log('created', 'projects', "Project baru: {$project->title}", $project);

        return $project;
    }

    public function update(Project $project, array $data): Project
    {
        $oldStatus = $project->status;
        $project->update($data);

        Activity::log('updated', 'projects', "Project diupdate: {$project->title}", $project);

        // Update client lifetime value when project is completed
        if ($oldStatus !== 'completed' && $project->status === 'completed') {
            $this->onProjectCompleted($project);
        }

        return $project;
    }

    protected function onProjectCompleted(Project $project): void
    {
        $client = $project->client;
        if ($client) {
            $client->update([
                'total_lifetime_value' => $client->total_lifetime_value + $project->price,
            ]);

            Activity::log('completed', 'projects', "Project {$project->title} selesai. LTV client +Rp " . number_format($project->price), $project);
        }
    }

    public function updateProgressFromMilestones(Project $project): Project
    {
        $total = $project->milestones()->count();
        if ($total === 0) {
            return $project;
        }

        $done = $project->milestones()->where('status', 'done')->count();
        $progress = round(($done / $total) * 100);

        $project->update(['progress_percentage' => $progress]);

        return $project;
    }

    public function addMilestone(Project $project, array $data): ProjectMilestone
    {
        $milestone = $project->milestones()->create($data);

        Activity::log('milestone_added', 'projects', "Milestone '{$milestone->title}' ditambahkan ke {$project->title}", $project);

        $this->updateProgressFromMilestones($project);

        return $milestone;
    }

    public function toggleMilestone(ProjectMilestone $milestone): ProjectMilestone
    {
        $milestone->update([
            'status' => $milestone->status === 'done' ? 'pending' : 'done',
        ]);

        $this->updateProgressFromMilestones($milestone->project);

        return $milestone;
    }
}
