select ac.username, ac.token from account_tokens at
left join account ac on ac.id=at.account_id
group by ac.username,ac.token;