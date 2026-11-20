-- Migration: Perbaiki Event Populer dan Tata Letak Homepage
-- Non-destruktif: UPDATE dan INSERT ... WHERE NOT EXISTS (DILARANG DROP, TRUNCATE, DELETE)

-- 1. Pastikan item_limit event_populer adalah 4
UPDATE homepage_sections 
SET item_limit = 4 
WHERE section_key = 'event_populer' AND (item_limit IS NULL OR item_limit < 4);

-- 2. Perbaiki kurasi featured_events untuk event_populer menjadi 4 baris sesuai urutan mockup & tanggal
UPDATE featured_events 
SET sort_order = 1 
WHERE section_key = 'event_populer' AND event_id = 1;

UPDATE featured_events 
SET sort_order = 3 
WHERE section_key = 'event_populer' AND event_id = 3;

INSERT INTO featured_events (section_key, event_id, sort_order, created_at)
SELECT 'event_populer', 1, 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM featured_events WHERE section_key = 'event_populer' AND event_id = 1);

INSERT INTO featured_events (section_key, event_id, sort_order, created_at)
SELECT 'event_populer', 2, 2, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM featured_events WHERE section_key = 'event_populer' AND event_id = 2);

INSERT INTO featured_events (section_key, event_id, sort_order, created_at)
SELECT 'event_populer', 3, 3, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM featured_events WHERE section_key = 'event_populer' AND event_id = 3);

INSERT INTO featured_events (section_key, event_id, sort_order, created_at)
SELECT 'event_populer', 4, 4, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM featured_events WHERE section_key = 'event_populer' AND event_id = 4);
