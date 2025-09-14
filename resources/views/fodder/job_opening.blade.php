*Job Opening Info*
job_opening_id: {{$job_opening->id}}
title: {{$job_opening->title}}
company_id: {{$job_opening->company_id}}
company_name: {{$job_opening->company->name}}
status: {{$job_opening->status}}
body: {{$job_opening->body}}
candidate_ids: {{$job_opening->candidate_ids}}

*Capability*
- View details of the job opening
- Apply for the job
- Update job status (for company/super_admin)
- View applied candidates (for company/super_admin)

*Constraint*
- Only candidates can apply
- Only the related company or super_admin can manage the job opening
- Non-affiliated users can only view details
