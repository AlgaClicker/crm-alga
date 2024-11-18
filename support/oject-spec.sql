SELECT o.name as objectName, cm.name as SpecName, o.id as objectId, s.idx, count(s.*) FROM objects o
LEFT JOIN specification s ON o.id = s.object_id
LEFt JOIN company cm ON cm.id = o.company_id
GROUP BY o.name,cm.name,o.id, s.idx
ORDER BY cm.name, o.name