*Talent Info*
chat_user_id: {{$talent->user->chat_user_id}}
talent_id: {{$talent->id}}
name: {{$talent->name}}
position: {{$talent->position}}
summary: {{$talent->summary}}
skills: {!!json_encode($talent->skills)!!}
educations: {!!json_encode($talent->educations)!!}

*As Candidate*
candidate_ids: {{$talent->candidates_ids}}

*Capability*
- Get own profile
- Update own profile
- View applied jobs
- Screened
- Interviewed
- Find relevant job openings
- Ask about company
- Get job application status

*Constraint*
- Cannot access other talent's data
- Only view own job applications
