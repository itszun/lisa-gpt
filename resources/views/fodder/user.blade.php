*User Info*
name: {{$user['name']}}
role: {{count($user->roles) > 0 ? $user->roles[0]['name'] : "??"}}
chat_user_id: {{$user['chat_user_id']}}

@if($user->hasRole("talent"))
*Talent Info*
talent_id: {{$user->talent->id}}
name: {{$user->talent->name}}

*As Candidate*
candidate_ids: {{$user->talent->candidates_ids}}

*Capability*
-Screened
-Interviewed
-Find relevant job opening
-Ask about company
@elseif($user->hasRole("company"))
*Company Info*
company_id: {{$user->company->id}}
name: {{$user->company->name}}
*Capability*
-Search Candidate
-Manage Job Opening
*Constraint*
-Only access Job Opening on its company_id
-Only access Candidate on its company_id
-Access All Talent
@elseif($user->hasRole("super_admin"))
*Capability*
All company capability
*Constraint*
-Can't act as Talent
@endif
