<?php

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;
use App\Business;
use App\Product;
use App\Image;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $sql = "
      INSERT INTO `images` (`id`, `name`, `format`, `url`, `base`, `path`, `created_at`, `updated_at`) VALUES
      (1, 'almohadas', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:21', '2020-07-13 07:59:21'),
      (2, 'brocoli', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:21', '2020-07-13 07:59:21'),
      (3, 'brujula', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:21', '2020-07-13 07:59:21'),
      (4, 'c_acuarios', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:21', '2020-07-13 07:59:21'),
      (5, 'c_bebes', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:22', '2020-07-13 07:59:22'),
      (6, 'c_carniceria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:22', '2020-07-13 07:59:22'),
      (7, 'c_carpinteria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:22', '2020-07-13 07:59:22'),
      (8, 'c_cerrajeros', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:23', '2020-07-13 07:59:23'),
      (9, 'c_chocolateria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:23', '2020-07-13 07:59:23'),
      (10, 'c_colchoneria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:23', '2020-07-13 07:59:23'),
      (11, 'c_electrodomesticos', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:23', '2020-07-13 07:59:23'),
      (12, 'c_farmacia', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:24', '2020-07-13 07:59:24'),
      (13, 'c_ferreteria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:24', '2020-07-13 07:59:24'),
      (14, 'c_fruteria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:24', '2020-07-13 07:59:24'),
      (15, 'c_imprenta', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:25', '2020-07-13 07:59:25'),
      (16, 'c_instrumentos', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:25', '2020-07-13 07:59:25'),
      (17, 'c_lacteos', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:25', '2020-07-13 07:59:25'),
      (18, 'c_lavanderia', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:25', '2020-07-13 07:59:25'),
      (19, 'c_limpieza', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:25', '2020-07-13 07:59:25'),
      (20, 'c_luces y lamparas', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:26', '2020-07-13 07:59:26'),
      (21, 'c_marisqueria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:51', '2020-07-13 07:59:51'),
      (22, 'c_panaderia', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:52', '2020-07-13 07:59:52'),
      (23, 'c_papelerias', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:52', '2020-07-13 07:59:52'),
      (24, 'c_pasteleria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:52', '2020-07-13 07:59:52'),
      (25, 'c_peluquerias', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:52', '2020-07-13 07:59:52'),
      (26, 'c_pescaderia', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:53', '2020-07-13 07:59:53'),
      (27, 'c_pinturas', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:53', '2020-07-13 07:59:53'),
      (28, 'c_protectoras', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:53', '2020-07-13 07:59:53'),
      (29, 'c_regalos', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:54', '2020-07-13 07:59:54'),
      (30, 'c_restaurantes', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:54', '2020-07-13 07:59:54'),
      (31, 'c_ropa', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:54', '2020-07-13 07:59:54'),
      (32, 'c_supervivencia', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:55', '2020-07-13 07:59:55'),
      (33, 'c_talleres', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:55', '2020-07-13 07:59:55'),
      (34, 'c_utensilios', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:55', '2020-07-13 07:59:55'),
      (35, 'c_verduras', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:55', '2020-07-13 07:59:55'),
      (36, 'c_veterinarios', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:56', '2020-07-13 07:59:56'),
      (37, 'c_viajes', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:56', '2020-07-13 07:59:56'),
      (38, 'c_zapateria', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:56', '2020-07-13 07:59:56'),
      (39, 'cama (1)', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:56', '2020-07-13 07:59:56'),
      (40, 'comestibles', 'png', 'categories', 'public', NULL, '2020-07-13 07:59:57', '2020-07-13 07:59:57'),
      (41, 'configuraciones', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:22', '2020-07-13 08:00:22'),
      (42, 'Copia de un-pan', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:23', '2020-07-13 08:00:23'),
      (43, 'ensalada (1)', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:23', '2020-07-13 08:00:23'),
      (44, 'ensalada', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:23', '2020-07-13 08:00:23'),
      (45, 'escoba', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:23', '2020-07-13 08:00:23'),
      (46, 'guacamayo', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:24', '2020-07-13 08:00:24'),
      (47, 'helado', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:24', '2020-07-13 08:00:24'),
      (48, 'lampara', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:24', '2020-07-13 08:00:24'),
      (49, 'magdalena', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:24', '2020-07-13 08:00:24'),
      (50, 'martillo', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:25', '2020-07-13 08:00:25'),
      (51, 'mazo', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:25', '2020-07-13 08:00:25'),
      (52, 'nachos', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:25', '2020-07-13 08:00:25'),
      (53, 'paleta-de-pintura', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:25', '2020-07-13 08:00:25'),
      (54, 'pavo', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:26', '2020-07-13 08:00:26'),
      (55, 'porcion-de-pizza', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:26', '2020-07-13 08:00:26'),
      (56, 'rosquilla', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:26', '2020-07-13 08:00:26'),
      (57, 'sandwich', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:27', '2020-07-13 08:00:27'),
      (58, 'tijeras', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:27', '2020-07-13 08:00:27'),
      (59, 'zapatos-para-correr', 'png', 'categories', 'public', NULL, '2020-07-13 08:00:27', '2020-07-13 08:00:27');";
      DB::statement($sql);
    }
}
