CREATE VIEW view_weeklyMenuShoppingList
AS
/* Compile a shopping list for a weekly menu */
SELECT
    weekly_menu_id,
    ingredient_id,
    item_name,
    category,
    ROUND(COALESCE(rounded_quantity, quantity),2) AS quantity,
    COALESCE(rounded_unit_type_id, unit_type_id) AS unit_type_id,
    COALESCE(rounded_unit_type, unit_type) AS unit_type,
    ingredient_dishes_json
FROM
    (
        SELECT
            WD.weekly_menu_id,
            I.id                 AS ingredient_id,
            I.ingredient_name    AS item_name,
            C.category_name      AS category,
            SUM(
                    CASE
                        WHEN COM.id IS NOT NULL THEN UT.component_quantity * DI.quantity
                        ELSE DI.quantity
                        END
                ) AS quantity,
            COALESCE(COM.id, UT.id) AS unit_type_id,
            COALESCE(COM.unit_type_abbr, UT.unit_type_abbr) AS unit_type,
            JSON_ARRAYAGG(
                    JSON_OBJECT(
                            'dish_name', D.dish_name,
                            'qty', ROUND(DI.quantity,2),
                            'unit', UT.unit_type_abbr
                        )
                ) ingredient_dishes_json
        FROM
            fp_weekly_menu_dish WD
            JOIN fp_dishes D ON
                D.id = WD.dish_id
            JOIN fp_dish_ingredient DI ON
                DI.dish_id = D.id
            JOIN fp_ingredients I ON
                I.id = DI.ingredient_id
            JOIN fp_unit_types_fl UT ON
                UT.id = DI.unit_type_id
            JOIN fp_ingredient_categories_fl C ON
                C.id = I.category_id
            JOIN fp_unit_type_categories_fl UTC ON
                UTC.id = UT.category_id
            LEFT JOIN fp_unit_types_fl COM ON
                COM.id = UT.unit_type_id_component
        WHERE
            D.deleted_at IS NULL AND
            I.deleted_at IS NULL
        GROUP BY
            WD.weekly_menu_id,
            I.id,
            I.ingredient_name,
            C.category_name,
            COALESCE(COM.id, UT.id),
            COALESCE(COM.unit_type_abbr, UT.unit_type_abbr)
    ) X
        /* Round to nearest measurement */
        LEFT JOIN LATERAL (
        SELECT
            UTX.id AS rounded_unit_type_id,
            UTX.unit_type_abbr AS rounded_unit_type,
            (X.quantity / UTX.component_quantity) AS rounded_quantity
        FROM
            fp_unit_types_fl UTX
        WHERE
            COALESCE(UTX.unit_type_id_component, UTX.id) = X.unit_type_id AND
            UTX.component_quantity <= X.quantity AND (
                    UTX.is_valid_for_rounding = 1
                    OR
                    MOD(UTX.component_quantity,X.quantity) = 0
                )
        ORDER BY
            UTX.component_quantity DESC
        LIMIT 1
        ) X2 ON 1=1

