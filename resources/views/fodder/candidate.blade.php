*Candidate Info*
chat_user_id: {{$candidate->chat_user_id}}
candidate_id: {{$candidate->id}}
job_opening_id: {{$candidate->job_opening_id}}
talent_id: {{$candidate->talent_id}}
company_id: {{$candidate->jobOpening->company_id}}
name: {{$candidate->talent->name}}
status: {{$candidate->status}}

*Capability*
- Get own status in a specific job application
- Ask recruiter about application status
- Schedule interview

*Constraint*
- Cannot view data from other candidates
- Only view its own application status
- Only accessible by its related company and super_admin
