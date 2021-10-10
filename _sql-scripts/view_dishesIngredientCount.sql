CREATE VIEW view_dishesIngredientCount
    AS
        SELECT
            D.household_id,
            D.id AS dish_id,
            D.dish_name,
            COUNT(DI.dish_id) AS ingredient_count
        FROM
            fp_dishes D
                LEFT JOIN fp_dish_ingredient DI ON
                DI.dish_id = D.id
        WHERE
            D.deleted_at IS NULL
        GROUP BY
            D.household_id,
            D.id,
            D.dish_name
