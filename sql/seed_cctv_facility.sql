-- Seed data for CCTV Facility June 2026 Inspection Schedule
-- Run this file in phpMyAdmin to populate reference data

-- 1. Add Inspectors (if not already exist)
INSERT IGNORE INTO users (id, fullname, email, username, password, role_id, status, created_at) VALUES
(2, 'Hatem Ben Brahim', 'hatem@facility.local', 'hatem', '$2y$10$haYqqZrgJDYv3vFsxf.FMOGRdKwY1i0I.wad4jcXMsKIwC2dA8dEe', 3, 'Active', NOW()),
(3, 'Rim Ben Boujemaa', 'rim@facility.local', 'rim', '$2y$10$haYqqZrgJDYv3vFsxf.FMOGRdKwY1i0I.wad4jcXMsKIwC2dA8dEe', 3, 'Active', NOW()),
(4, 'Ahlem Lellahom', 'ahlem@facility.local', 'ahlem', '$2y$10$haYqqZrgJDYv3vFsxf.FMOGRdKwY1i0I.wad4jcXMsKIwC2dA8dEe', 3, 'Active', NOW()),
(5, 'Dalel Ben Dhieb', 'dalel@facility.local', 'dalel', '$2y$10$haYqqZrgJDYv3vFsxf.FMOGRdKwY1i0I.wad4jcXMsKIwC2dA8dEe', 3, 'Active', NOW());

-- 2. Add CCTV Facility Site
INSERT IGNORE INTO sites (id, site_name, address, status) VALUES
(1, 'CCTV Facility 2026', 'Main Facility Location', 1);

-- 3. Add Checklist Templates for CCTV Inspections
INSERT IGNORE INTO checklist_templates (id, template_name, description, created_at) VALUES
(1, 'Gerbeurs/Tracteurs', 'Forklift and tractor equipment safety inspection', NOW()),
(2, 'Blocs Autonomes et Affichage de Sécurité', 'Emergency lighting and safety signage verification', NOW()),
(3, 'Vérification des Zones de Stockage Extérieurs', 'External storage areas safety check', NOW()),
(4, 'Portes Coupe-Feu', 'Fire door inspection and functionality', NOW()),
(5, 'Quais de Chargement', 'Loading dock safety and equipment', NOW()),
(6, 'Kit d''Urgence', 'Emergency kit completeness and readiness', NOW()),
(7, 'Compresseur/Groupe Électrogène', 'Air compressor and generator inspection', NOW()),
(8, 'Vérification des BBG Verts et Rouges', 'Safety ball valve verification', NOW()),
(9, 'Bac à Sable / Eye Wash', 'Sand bin and eye wash station inspection', NOW()),
(10, 'Vérification des Détecteurs', 'Detector and alarm system check', NOW()),
(11, 'Armoires Électriques', 'Electrical panel safety inspection', NOW()),
(12, 'Portes Secours', 'Emergency exit door verification', NOW()),
(13, 'Kit de Déversement', 'Spill kit completeness and accessibility', NOW()),
(14, 'Vérification des Extincteurs', 'Fire extinguisher inspection and maintenance', NOW()),
(15, 'TGBT/Local Transformateur', 'Main electrical distribution and transformer room', NOW()),
(16, 'Pompe à Eau+RIA', 'Water pump and emergency systems', NOW());

-- Note: After running this, use the import.php tool to bulk load the June 2026 schedule
-- from CSV file