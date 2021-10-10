CREATE VIEW view_dishesMenuCount
AS
SELECT
    D.household_id,
    D.id                AS dish_id,
    D.dish_name,
    COUNT(FWMD.dish_id) AS menu_count
FROM
    fp_dishes D
        LEFT JOIN fp_weekly_menu_dish FWMD ON
        FWMD.dish_id = D.id
WHERE
    D.deleted_at IS NULL
GROUP BY
    D.household_id,
    D.id,
    D.dish_name
