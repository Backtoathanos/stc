<?php
/**
 * Local AI assistant config — Ollama on this machine only.
 * Do not deploy this to production server.
 */
return [
	'ollama_url'    => 'http://127.0.0.1:11434',
	'model'         => 'llama3.2',
	'timeout_sec'   => 120,

	// true = saari stc_* tables use kar sakte ho
	'allow_all_stc_tables' => true,

	'allowed_tables' => [
		'stc_product',
		'stc_category',
		'stc_sub_category',
		'stc_brand',
		'stc_rack',
		'stc_item_inventory',
		'stc_merchant',
		'stc_purchase_product',
		'stc_purchase_product_items',
		'stc_purchase_product_adhoc',
		'stc_shop',
		'stc_product_grn',
		'stc_product_grn_items',
		'stc_customer',
		'stc_cust_project',
		'stc_sale_product',
		'stc_sale_product_items',
	],

	'max_select_rows' => 200,

	// Local: AI ka SQL khud chalega (SELECT + UPDATE + INSERT + ALTER add/drop column)
	'auto_execute_sql' => true,
	'allow_write_sql'  => true,
	'allow_alter_table' => true,

	'export_dir' => __DIR__ . '/../ai_exports',
];
