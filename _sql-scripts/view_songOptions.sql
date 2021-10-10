CREATE VIEW view_songOptions
AS
SELECT
    SO.user_id,
    SO.id AS value,
    CONCAT(
            IF(
                SO.song_composer IS NOT NULL,
                CONCAT(SO.song_composer, ' - '),
                ''
            ),
            SO.song_title,
            IF(
                X.HasLyrics+X.HasChords > 0,
                CONCAT(
                        ' (',
                        IF(X.HasLyrics = 1,'L',''),
                        IF(X.HasBoth = 1,'/',''),
                        IF(X.HasChords,'C',''),
                        ')'
                    ),
                ''
            )
        ) AS label
FROM
    sa_songs SO
    JOIN LATERAL (
    SELECT
        IF(SO.song_lyrics IS NOT NULL, 1, 0) AS HasLyrics,
        IF(SO.song_chords IS NOT NULL, 1, 0) AS HasChords,
        IF(SO.song_lyrics IS NOT NULL AND SO.song_chords IS NOT NULL, 1, 0) AS HasBoth
    ) X ON 1=1
WHERE
    SO.deleted_at IS NULL
