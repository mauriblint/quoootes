-- Indexes for the design listings (/quotes, /quotes/{tag}, /p/{shortcode}, dashboard).
-- Without them every listing scans the whole designs table.
ALTER TABLE designs
    ADD INDEX idx_status_created (status, created),
    ADD INDEX idx_tag_status_created (tag_id, status, created),
    ADD INDEX idx_user_status (user_id, status),
    ADD INDEX idx_likes_created (likes, created);
