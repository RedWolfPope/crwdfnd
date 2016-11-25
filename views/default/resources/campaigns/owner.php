<?php

$username = elgg_extract('username', $vars);
$entity_guid = elgg_extract('guid', $vars);
if ($entity_guid) {
	$entity = get_entity($entity_guid);
} else {
	$entity = get_user_by_username($username);
}

if (!$entity instanceof ElggEntity) {
	forward('', '404');
}

elgg_group_gatekeeper(true, $entity->guid);

elgg_set_page_owner_guid($entity->guid);

elgg_register_title_button('campaigns', 'add', 'object', SBW\Campaigns\Campaign::SUBTYPE);

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
if ($entity instanceof ElggUser) {
	elgg_push_breadcrumb($entity->getDisplayName(), "/campaigns/owner/$entity->username");
	$title = elgg_echo('campaigns:owner', [$entity->getDisplayName()]);
} else {
	elgg_push_breadcrumb($entity->getDisplayName(), "/campaigns/group/$entity->guid");
	$title = elgg_echo('campaigns:group', [$entity->getDisplayName()]);
}

if ($entity->guid == elgg_get_logged_in_user_guid()) {
	$vars['filter_context'] = 'mine';
}

$vars['entity'] = $entity;

$content = elgg_view('campaigns/listing/owner', $vars);
$sidebar = elgg_view('campaigns/sidebar', $vars);

$layout = elgg_view_layout('content', $vars + [
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
	'filter_context' => $entity->guid == elgg_get_logged_in_user_guid() ? 'mine' : '',
		]);

echo elgg_view_page($title, $layout, 'default', $vars);