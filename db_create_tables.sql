CREATE TABLE admin_apps
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    app_name       varchar(100) NOT NULL,
    browser_title  varchar(100) NOT NULL,
    route_start    varchar(100) NOT NULL,
    navbar_color   varchar(100) NOT NULL,
    icon           varchar(100) NOT NULL,
    favicon        varchar(100) NOT NULL,
    is_home_app    tinyint(1)   NOT NULL,
    is_active      tinyint(1)   NOT NULL,
    is_development tinyint(1)   NOT NULL,
    sort_order     int UNSIGNED NOT NULL
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE admin_nav_groups
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    app_id         bigint UNSIGNED NOT NULL,
    nav_group_name varchar(100)    NOT NULL,
    route_start    varchar(100)    NOT NULL,
    icon           varchar(100)    NOT NULL,
    is_active      tinyint(1)      NOT NULL,
    is_development tinyint(1)      NOT NULL,
    sort_order     int UNSIGNED    NOT NULL,
    CONSTRAINT admin_nav_groups_app_id_foreign
        FOREIGN KEY (app_id) REFERENCES admin_apps (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE admin_nav_items
(
    id                        bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    nav_group_id              bigint UNSIGNED      NOT NULL,
    route                     varchar(100)         NOT NULL,
    nav_item_name             varchar(100)         NOT NULL,
    sort_order                int UNSIGNED         NOT NULL,
    do_use_item_name_as_title tinyint(1) DEFAULT 0 NOT NULL,
    CONSTRAINT admin_nav_items_nav_group_id_foreign
        FOREIGN KEY (nav_group_id) REFERENCES admin_nav_groups (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE calendar_table
(
    date        date                   NOT NULL
        PRIMARY KEY,
    day_no      tinyint UNSIGNED       NOT NULL,
    month_no    tinyint UNSIGNED       NOT NULL,
    month_name  varchar(20)            NOT NULL,
    day_name    varchar(20) DEFAULT '' NOT NULL,
    week_no     tinyint UNSIGNED       NOT NULL,
    day_of_week tinyint UNSIGNED       NOT NULL,
    year        int UNSIGNED           NOT NULL
)
    CHARSET = latin1;

CREATE TABLE dev_log
(
    id          int AUTO_INCREMENT
        PRIMARY KEY,
    log_message varchar(1000) NOT NULL,
    created_at  timestamp     NULL,
    updated_at  timestamp     NULL
);

CREATE TABLE fp_dish_categories
(
    id                 bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    dish_category_name varchar(100)    NOT NULL,
    sort_order         int             NOT NULL,
    household_id       bigint UNSIGNED NOT NULL,
    user_id_insert     bigint UNSIGNED NOT NULL,
    user_id_update     bigint UNSIGNED NOT NULL,
    created_at         timestamp       NULL,
    updated_at         timestamp       NULL,
    deleted_at         timestamp       NULL
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_ingredient_categories_fl
(
    id            bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    category_name varchar(100) DEFAULT '' NOT NULL,
    is_default    tinyint(1)              NOT NULL
)
    CHARSET = latin1;

CREATE TABLE fp_unit_type_categories_fl
(
    id            int UNSIGNED     NOT NULL,
    category_name varchar(100)     NOT NULL,
    is_volume     tinyint UNSIGNED NOT NULL,
    is_weight     tinyint UNSIGNED NOT NULL,
    is_other      tinyint UNSIGNED NOT NULL,
    CONSTRAINT fp_unit_type_categories_fl_category_name_uindex
        UNIQUE (category_name),
    CONSTRAINT fp_unit_type_categories_fl_id_uindex
        UNIQUE (id)
)
    CHARSET = latin1;

ALTER TABLE fp_unit_type_categories_fl
    ADD PRIMARY KEY (id);

CREATE TABLE fp_unit_types_fl
(
    id                     bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    unit_type_name         varchar(20)  DEFAULT ''  NOT NULL,
    unit_type_abbr         varchar(20)  DEFAULT ''  NOT NULL,
    sort_order             int                      NOT NULL,
    is_default             tinyint(1)   DEFAULT 0   NOT NULL,
    category_id            int UNSIGNED DEFAULT '1' NOT NULL,
    unit_type_id_component bigint UNSIGNED          NULL,
    component_quantity     int                      NULL,
    is_valid_for_rounding  tinyint(1)   DEFAULT 0   NOT NULL,
    CONSTRAINT fp_unit_types_fl_category_id_foreign
        FOREIGN KEY (category_id) REFERENCES fp_unit_type_categories_fl (id),
    CONSTRAINT fp_unit_types_fl_unit_type_id_component_foreign
        FOREIGN KEY (unit_type_id_component) REFERENCES fp_unit_types_fl (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_ingredients
(
    id              bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    ingredient_name varchar(255)    NOT NULL,
    unit_type_id    bigint UNSIGNED NOT NULL COMMENT 'Default unit type that will be pre-selected when creating a new dish.',
    category_id     bigint UNSIGNED NOT NULL,
    user_id_insert  bigint UNSIGNED NOT NULL,
    user_id_update  bigint UNSIGNED NOT NULL,
    created_at      timestamp       NULL,
    updated_at      timestamp       NULL,
    deleted_at      timestamp       NULL,
    CONSTRAINT fp_ingredients_category_id_foreign
        FOREIGN KEY (category_id) REFERENCES fp_ingredient_categories_fl (id),
    CONSTRAINT fp_ingredients_unit_type_id_foreign
        FOREIGN KEY (unit_type_id) REFERENCES fp_unit_types_fl (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE migrations
(
    id        int UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    migration varchar(255) NOT NULL,
    batch     int          NOT NULL
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE password_resets
(
    email      varchar(255) NOT NULL,
    token      varchar(255) NOT NULL,
    created_at timestamp    NULL
)
    COLLATE = utf8mb4_unicode_ci;

CREATE INDEX password_resets_email_index
    ON password_resets (email);

CREATE TABLE users
(
    id                bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    name              varchar(255)         NOT NULL,
    email             varchar(255)         NOT NULL,
    email_verified_at timestamp            NULL,
    password          varchar(255)         NOT NULL,
    remember_token    varchar(100)         NULL,
    is_admin          tinyint(1) DEFAULT 0 NOT NULL,
    created_at        timestamp            NULL,
    updated_at        timestamp            NULL,
    CONSTRAINT users_email_unique
        UNIQUE (email)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_households
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    household_name varchar(255)    NOT NULL,
    user_id_insert bigint UNSIGNED NOT NULL,
    user_id_update bigint UNSIGNED NOT NULL,
    created_at     timestamp       NULL,
    updated_at     timestamp       NULL,
    deleted_at     timestamp       NULL,
    CONSTRAINT fp_households_user_id_insert_foreign
        FOREIGN KEY (user_id_insert) REFERENCES users (id),
    CONSTRAINT fp_households_user_id_update_foreign
        FOREIGN KEY (user_id_update) REFERENCES users (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_dishes
(
    id               bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    dish_name        varchar(100)    NOT NULL,
    dish_description varchar(5000)   NULL,
    dish_rating      int             NULL,
    dish_difficulty  int             NULL,
    portions         tinyint         NULL,
    url_recipe       varchar(1000)   NULL,
    household_id     bigint UNSIGNED NOT NULL,
    user_id_insert   bigint UNSIGNED NOT NULL,
    user_id_update   bigint UNSIGNED NOT NULL,
    created_at       timestamp       NULL,
    updated_at       timestamp       NULL,
    deleted_at       timestamp       NULL,
    archived_at      timestamp       NULL,
    dish_category_id bigint UNSIGNED NULL,
    CONSTRAINT fp_dishes_household_id_foreign
        FOREIGN KEY (household_id) REFERENCES fp_households (id),
    CONSTRAINT fp_dishes_user_id_insert_foreign
        FOREIGN KEY (user_id_insert) REFERENCES users (id),
    CONSTRAINT fp_dishes_user_id_update_foreign
        FOREIGN KEY (user_id_update) REFERENCES users (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_dish_ingredient
(
    id            bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    dish_id       bigint UNSIGNED       NOT NULL,
    ingredient_id bigint UNSIGNED       NOT NULL,
    quantity      float(19, 2) UNSIGNED NOT NULL,
    unit_type_id  bigint UNSIGNED       NOT NULL,
    CONSTRAINT fp_dish_ingredient_dish_id_foreign
        FOREIGN KEY (dish_id) REFERENCES fp_dishes (id)
            ON DELETE CASCADE,
    CONSTRAINT fp_dish_ingredient_ingredient_id_foreign
        FOREIGN KEY (ingredient_id) REFERENCES fp_ingredients (id),
    CONSTRAINT fp_dish_ingredient_unit_type_id
        FOREIGN KEY (unit_type_id) REFERENCES fp_unit_types_fl (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_household_member
(
    id           bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    household_id bigint UNSIGNED NOT NULL,
    user_id      bigint UNSIGNED NOT NULL,
    CONSTRAINT fp_household_member_household_id_foreign
        FOREIGN KEY (household_id) REFERENCES fp_households (id)
            ON DELETE CASCADE,
    CONSTRAINT fp_household_member_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
            ON DELETE CASCADE
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_weekly_menus
(
    id              bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    date_week_start date             NOT NULL,
    week_no         tinyint          NULL,
    menu_rating     tinyint UNSIGNED NULL,
    household_id    bigint UNSIGNED  NOT NULL,
    user_id_insert  bigint UNSIGNED  NOT NULL,
    user_id_update  bigint UNSIGNED  NOT NULL,
    created_at      timestamp        NULL,
    updated_at      timestamp        NULL,
    deleted_at      timestamp        NULL,
    CONSTRAINT fp_weekly_menus_household_id_foreign
        FOREIGN KEY (household_id) REFERENCES fp_households (id),
    CONSTRAINT fp_weekly_menus_user_id_insert_foreign
        FOREIGN KEY (user_id_insert) REFERENCES users (id),
    CONSTRAINT fp_weekly_menus_user_id_update_foreign
        FOREIGN KEY (user_id_update) REFERENCES users (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_shopping_lists
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    weekly_menu_id bigint UNSIGNED NOT NULL,
    user_id_insert bigint UNSIGNED NOT NULL,
    user_id_update bigint UNSIGNED NOT NULL,
    created_at     timestamp       NULL,
    updated_at     timestamp       NULL,
    deleted_at     timestamp       NULL,
    CONSTRAINT fp_shopping_list_headers_user_id_insert_foreign
        FOREIGN KEY (user_id_insert) REFERENCES users (id),
    CONSTRAINT fp_shopping_list_headers_user_id_update_foreign
        FOREIGN KEY (user_id_update) REFERENCES users (id),
    CONSTRAINT fp_shopping_list_headers_weekly_menu_id_foreign
        FOREIGN KEY (weekly_menu_id) REFERENCES fp_weekly_menus (id)
            ON DELETE CASCADE
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_shopping_list_rows
(
    id                  bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    shopping_list_id    bigint UNSIGNED         NOT NULL,
    item_name           varchar(255) DEFAULT '' NOT NULL,
    unit_type           varchar(30)             NULL,
    quantity            float(19, 2) UNSIGNED   NOT NULL,
    category            varchar(255)            NULL,
    weekly_menu_dishes  varchar(500)            NULL COMMENT 'List of dishes that utilizes the item',
    is_weekly_menu_item tinyint(1)   DEFAULT 0  NOT NULL,
    checked_at          timestamp               NULL,
    user_id_checked     bigint UNSIGNED         NULL,
    CONSTRAINT fp_shopping_list_rows_shopping_list_header_id_foreign
        FOREIGN KEY (shopping_list_id) REFERENCES fp_shopping_lists (id)
            ON DELETE CASCADE,
    CONSTRAINT fp_shopping_list_rows_user_id_checked_foreign
        FOREIGN KEY (user_id_checked) REFERENCES users (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE fp_weekly_menu_dish
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    weekly_menu_id bigint UNSIGNED NOT NULL,
    dish_id        bigint UNSIGNED NOT NULL,
    day_of_week    tinyint         NOT NULL,
    CONSTRAINT fp_weekly_menu_dish_dish_id_foreign
        FOREIGN KEY (dish_id) REFERENCES fp_dishes (id)
            ON DELETE CASCADE,
    CONSTRAINT fp_weekly_menu_dish_weekly_menu_id_foreign
        FOREIGN KEY (weekly_menu_id) REFERENCES fp_weekly_menus (id)
            ON DELETE CASCADE
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE lm_todo_groups
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    group_name     varchar(255)                 NOT NULL,
    color_bg       varchar(255)                 NOT NULL,
    color_text     varchar(255)                 NOT NULL,
    sort_order     varchar(255)                 NOT NULL,
    start_expanded tinyint UNSIGNED DEFAULT '0' NOT NULL,
    user_id        bigint UNSIGNED              NOT NULL,
    created_at     timestamp                    NULL,
    updated_at     timestamp                    NULL,
    deleted_at     timestamp                    NULL,
    CONSTRAINT ar_todo_groups_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE lm_todo_items
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    item_name      varchar(255)                 NOT NULL,
    group_id       bigint UNSIGNED              NOT NULL,
    date_deadline  date                         NULL,
    is_urgent      tinyint UNSIGNED             NOT NULL,
    priority_order int                          NULL,
    user_id        bigint UNSIGNED              NOT NULL,
    created_at     timestamp                    NULL,
    updated_at     timestamp                    NULL,
    deleted_at     timestamp                    NULL,
    is_checked     tinyint UNSIGNED DEFAULT '0' NOT NULL,
    CONSTRAINT ar_todo_items_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id),
    CONSTRAINT lm_todo_items_group_id_foreign
        FOREIGN KEY (group_id) REFERENCES lm_todo_groups (id)
            ON DELETE CASCADE
)
    COLLATE = utf8mb4_unicode_ci;

CREATE INDEX ar_todo_items_group_id_foreign
    ON lm_todo_items (group_id);

CREATE TABLE sa_song_books
(
    id                    bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    song_book_title       varchar(100)    NOT NULL,
    song_book_description varchar(1000)   NULL,
    user_id               bigint UNSIGNED NOT NULL,
    created_at            timestamp       NULL,
    updated_at            timestamp       NULL,
    deleted_at            timestamp       NULL,
    url_suffix            varchar(100)    NOT NULL,
    CONSTRAINT sa_song_books_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE INDEX sa_song_books_user_id_insert_foreign
    ON sa_song_books (user_id);

CREATE TABLE sa_songs
(
    id             bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    song_title     varchar(100)    NOT NULL,
    song_composer  varchar(100)    NULL,
    song_lyrics    text            NULL,
    song_chords    text            NULL,
    sheet_file_url varchar(100)    NULL,
    starting_note  varchar(10)     NULL,
    capo_fret_no   int             NULL COMMENT 'Guitar fret no to place capo on',
    user_id        bigint UNSIGNED NOT NULL,
    created_at     timestamp       NULL,
    updated_at     timestamp       NULL,
    deleted_at     timestamp       NULL,
    CONSTRAINT sa_songs_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
)
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE sa_song_book_song
(
    id           bigint UNSIGNED AUTO_INCREMENT
        PRIMARY KEY,
    song_book_id bigint UNSIGNED NOT NULL,
    song_id      bigint UNSIGNED NOT NULL,
    sort_order   int UNSIGNED    NOT NULL,
    CONSTRAINT sa_song_book_song_song_book_id_foreign
        FOREIGN KEY (song_book_id) REFERENCES sa_song_books (id)
            ON DELETE CASCADE,
    CONSTRAINT sa_song_book_song_song_id_foreign
        FOREIGN KEY (song_id) REFERENCES sa_songs (id)
            ON DELETE CASCADE
)
    COLLATE = utf8mb4_unicode_ci;

CREATE INDEX sa_songs_user_id_insert_foreign
    ON sa_songs (user_id);