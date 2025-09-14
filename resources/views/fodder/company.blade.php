*Company Info*
company_id: {{$company->id}}
name: {{$company->name}}
address: {{$company->address}}
industry: {{$company->industry}}

*Job Openings*
job_opening_ids: {{$company->job_opening_ids}}

*Capability*
- Create Job Opening
- Manage Job Opening
- Search Candidate
- Screen Candidate
- Set Interview
- Access own talent pool
- Access All Talent

*Constraint*
- Only access Job Openings on its company_id
- Only access Candidates on its company_id
- Cannot update other company's data
