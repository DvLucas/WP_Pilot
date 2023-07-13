<?php
/*
Plugin Name: Leads Pilot Solution
Description: Envia los datos de formularios de WPForms como leads a Pilot Solution
Version: 1.0
Author: Lucas Benitez
*/
/*
Formularios:
Quiero Ser Cliente - ID: 1906
Whatsapp - ID: 1897
*/
add_action( 'wpforms_process_complete', 'tu_funcion', 10, 4 );

function tu_funcion( $fields, $entry, $form_data, $entry_id ) {
	if ($form_data['id'] === '1906' || $form_data['id'] === '1897')
	{
		$serviceURL = "https://api.pilotsolution.net/webhooks/welcome.php";
		$appKey = "4A2A14E8-8628-430B-B174-336A154D709F"; 
		$tipoNegocio = "1";  
		$origendeldato = "ESDR4JXUIH7Y797BK"; 
		$landing_link = "https://marquezyasociados.com.ar/contacto/"; 

		// Parámetros que pueden venir de un formulario
		$params = array(
			'action' => 'create',
			'appkey' => $appKey,
			'pilot_contact_type_id' => '1', // electrónico
			'pilot_business_type_id' => $tipoNegocio,
			'pilot_suborigin_id' => $origendeldato,
			'pilot_provider_url' => $landing_link
		);

		// Procesa los datos del formulario y agrega los parámetros necesarios
		if ( $form_data['id'] === '1906' ) {
			$params['pilot_firstname'] = sanitize_text_field( $fields['1']['value'] );
			$params['pilot_email'] = sanitize_text_field( $fields['2']['value'] );
			$params['pilot_cellphone'] = sanitize_text_field( $fields['3']['value'] );
			$params['pilot_notes'] = sanitize_text_field( $fields['4']['value'] );
		}
		else if ('1897') {
			$params['pilot_firstname'] = sanitize_text_field($fields['1']['value']);
			$params['pilot_cellphone'] = sanitize_text_field($fields['2']['value']);
		}
		// Envía la solicitud al servicio web utilizando wp_remote_post()
		$response = wp_remote_post($serviceURL, array(
			'body' => $params,
			'timeout' => 50,
			'sslverify' => false // desactivar la verificación SSL si es necesario
		));
	}
}