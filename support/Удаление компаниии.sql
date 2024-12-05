DELETE FROM account 
WHERE company_id in (SELECT cm.id FROM company cm
WHERE cm.created_at BETWEEN '2024-09-01' and '2024-12-31');

DELETE FROM profession  WHERE company_id in (SELECT cm.id FROM company cm
WHERE cm.created_at BETWEEN '2024-09-01' and '2024-12-31');

DELETE FROM stock  WHERE company_id in (SELECT cm.id FROM company cm
WHERE cm.created_at BETWEEN '2024-09-01' and '2024-12-31');

DELETE FROM roles  WHERE company_id in (SELECT cm.id FROM company cm
WHERE cm.created_at BETWEEN '2024-09-01' and '2024-12-31');


DELETE FROM company WHERE created_at BETWEEN '2024-09-01' and '2024-12-31';

