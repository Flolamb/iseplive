<?php
/**
 * Configuration of the routes
 */

final class Routes extends RoutesAbstract {
	
	/**
	 * List of the routes
	 *
	 *	{
	 *		"module" : {	// Name of the route. e.g. : 'user_view'
	 *			regexp : regular expression (PCRE) matching with the url. e.g. : '^user/([a-z0-9]+)/(?=\?|$)'
	 *			vars : variables corresponding to the url, with values of the previous regexp. e.g. : 'controller=User&action=view&username=$1'
	 *			url : URL of the page depending on various parameters ({id}, {title}...). e.g. : 'users/{username}/'
	 *			extend (optionnal) : {
	 *				"vars1" : "module_extended1"
	 *				"vars2" : "module_extended2"
	 *				...
	 *				// vars1 = names of additional variables (seperated by &). e.g. : 'page'
	 *				// module_extended1 = route name to use when these variables are defined : 'user_view_page'
	 *			}
	 *		},
	 *	...}
	 *
	 * @static array
	 */
	protected static $routes =	array(
		// Home
		'posts'	=> array(
			'regexp'	=> '^(?=\?|$)',
			'vars'		=> 'controller=Post&action=index',
			'url'		=> ''
		),
		'posts_ajax_page'	=> array(
			'regexp'	=> '^ajax/posts/([01])/([1-9][0-9]*)(?=\?|$)',
			'vars'		=> 'controller=Post&action=index_ajax&official=$1&page=$2&mode=raw',
			'url'		=> 'ajax/posts/{official}/{page}'
		),
		
		// Posts by category
		'posts_category'	=> array(
			'regexp'	=> '^category/([a-zA-Z0-9_-]+)(?=\?|$)',
			'vars'		=> 'controller=Post&action=index&category=$1',
			'url'		=> 'category/{category}'
		),
		'posts_category_ajax_page'	=> array(
			'regexp'	=> '^ajax/category/([a-zA-Z0-9_-]+)/([01])/([1-9][0-9]*)(?=\?|$)',
			'vars'		=> 'controller=Post&action=index_ajax&category=$1&official=$2&page=$3&mode=raw',
			'url'		=> 'ajax/category/{category}/{official}/{page}'
		),
		
		
		// Post
		'post'	=> array(
			'regexp'	=> '^post/([0-9]+)(?=\?|$)',
			'vars'		=> 'controller=Post&action=view&id=$1',
			'url'		=> 'post/{id}'
		),
		
		// Add a post
		'post_add'	=> array(
			'regexp'	=> '^post/add(?=\?|$)',
			'vars'		=> 'controller=Post&action=iframe_add&mode=iframe',
			'url'		=> 'post/add'
		),
		
		// Delete post
		'post_delete'	=> array(
			'regexp'	=> '^ajax/post/([0-9]+)/delete(?=\?|$)',
			'vars'		=> 'controller=Post&action=delete&id=$1&mode=json',
			'url'		=> 'ajax/post/{id}/delete'
		),
		
		// Add a comment to a post
		'post_comment'	=> array(
			'regexp'	=> '^ajax/post/([0-9]+)/comment/add(?=\?|$)',
			'vars'		=> 'controller=PostComment&action=add&post_id=$1&mode=raw',
			'url'		=> 'ajax/post/{id}/comment/add'
		),
		
		// Delete post
		'post_comment_delete'	=> array(
			'regexp'	=> '^ajax/post/comment/([0-9]+)/delete(?=\?|$)',
			'vars'		=> 'controller=PostComment&action=delete&id=$1&mode=json',
			'url'		=> 'ajax/post/comment/{id}/delete'
		),
		
		// Events' posts in a month
		'events'	=> array(
			'regexp'	=> '^events/([0-9]{4})/([0-9]{2})(?=\?|$)',
			'vars'		=> 'controller=Post&action=events&year=$1&month=$2',
			'url'		=> 'events/{year}/{month}',
			'extend'	=> array(
				'day&association'	=> 'association_events_day',
				'day'	=> 'events_day',
				'association'	=> 'association_events'
			)
		),
		'association_events'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)/events/([0-9]{4})/([0-9]{2})(?=\?|$)',
			'vars'		=> 'controller=Post&action=events&association=$1&year=$2&month=$3',
			'url'		=> 'association/{association}/events/{year}/{month}',
			'extend'	=> array(
				'day'	=> 'events_association_day'
			)
		),
		// Events' posts in a day
		'events_day'	=> array(
			'regexp'	=> '^events/([0-9]{4})/([0-9]{2})/([0-9]{2})(?=\?|$)',
			'vars'		=> 'controller=Post&action=events&year=$1&month=$2&day=$3',
			'url'		=> 'events/{year}/{month}/{day}',
			'extend'	=> array(
				'association'	=> 'association_events_day'
			)
		),
		'association_events_day'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)/events/([0-9]{4})/([0-9]{2})/([0-9]{2})(?=\?|$)',
			'vars'		=> 'controller=Post&action=events&association=$1&year=$2&month=$3&day=$4',
			'url'		=> 'association/{association}/events/{year}/{month}/{day}'
		),
		
		// iCal : Official events
		'ical_official'	=> array(
			'regexp'	=> '^events/calendar-official.ics(?=\?|$)',
			'vars'		=> 'controller=Event&action=ical&official&mode=raw',
			'url'		=> 'events/calendar-official.ics',
			'extend'	=> array(
				'association'	=> 'association_ical_official'
			)
		),
		'association_ical_official'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)/events/calendar-official.ics(?=\?|$)',
			'vars'		=> 'controller=Event&action=ical&official&association=$1&mode=raw',
			'url'		=> 'association/{association}/events/calendar-official.ics'
		),
		// iCal : Non official events
		'ical_non_official'	=> array(
			'regexp'	=> '^events/calendar-students.ics(?=\?|$)',
			'vars'		=> 'controller=Event&action=ical&mode=raw',
			'url'		=> 'events/calendar-students.ics',
			'extend'	=> array(
				'association'	=> 'association_ical_non_official'
			)
		),
		'association_ical_non_official'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)/events/calendar-students.ics(?=\?|$)',
			'vars'		=> 'controller=Event&action=ical&association=$1&mode=raw',
			'url'		=> 'association/{association}/events/calendar-students.ics'
		),
		
		// Vote for a survey
		'survey_vote'	=> array(
			'regexp'	=> '^ajax/survey/vote/([0-9]+)(?=\?|$)',
			'vars'		=> 'controller=Survey&action=vote&id=$1&mode=raw',
			'url'		=> 'ajax/survey/vote/{id}'
		),
		
		// Students profiles
		'student'	=> array(
			'regexp'	=> '^student/([a-z0-9-]+)(?=\?|$)',
			'vars'		=> 'controller=Student&action=view&username=$1',
			'url'		=> 'student/{username}'
		),
		
		// Association's page
		'association'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)(?=\?|$)',
			'vars'		=> 'controller=Association&action=view&association=$1',
			'url'		=> 'association/{association}'
		),
		'association_posts_ajax_page'	=> array(
			'regexp'	=> '^ajax/association/([a-z0-9-]+)/posts/([1-9][0-9]*)(?=\?|$)',
			'vars'		=> 'controller=Post&action=index_ajax&association=$1&page=$2&mode=raw',
			'url'		=> 'ajax/association/{association}/posts/{page}'
		),
		'association_posts_category'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)/category/([a-zA-Z0-9_-]+)(?=\?|$)',
			'vars'		=> 'controller=Association&action=view&association=$1&category=$2',
			'url'		=> 'association/{association}/category/{category}'
		),
		'association_posts_category_ajax_page'	=> array(
			'regexp'	=> '^ajax/association/([a-z0-9-]+)/category/([a-zA-Z0-9_-]+)/([1-9][0-9]*)(?=\?|$)',
			'vars'		=> 'controller=Post&action=index_ajax&association=$1&category=$2&page=$3&mode=raw',
			'url'		=> 'ajax/association/{association}/category/{category}/{page}'
		),
		'association_edit'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)/edit(?=\?|$)',
			'vars'		=> 'controller=Association&action=edit&association=$1',
			'url'		=> 'association/{association}/edit'
		),
		'association_delete'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)/delete(?=\?|$)',
			'vars'		=> 'controller=Association&action=delete&association=$1',
			'url'		=> 'association/{association}/delete'
		),
		'association_add'	=> array(
			'regexp'	=> '^association-add(?=\?|$)',
			'vars'		=> 'controller=Association&action=add',
			'url'		=> 'association-add'
		),
		
		
		// Associations' list
		'associations'	=> array(
			'regexp'	=> '^associations(?=\?|$)',
			'vars'		=> 'controller=Association&action=index',
			'url'		=> 'associations'
		),
		
		// Sign-in
		'signin'	=> array(
			'regexp'	=> '^signin$',
			'vars'		=> 'controller=User&action=signin&redirect=/',
			'url'		=> 'signin',
			'extend'	=> array(
				'redirect'	=> 'signin_redirect'
			)
		),
		'signin_redirect'	=> array(
			'regexp'	=> '^signin(/.*)$',
			'vars'		=> 'controller=User&action=signin&redirect=$1',
			'url'		=> 'signin{redirect}'
		),
		// Logout
		'logout'	=> array(
			'regexp'	=> '^logout$',
			'vars'		=> 'controller=User&action=logout&redirect=/',
			'url'		=> 'logout',
			'extend'	=> array(
				'redirect'	=> 'logout_redirect'
			)
		),
		'logout_redirect'	=> array(
			'regexp'	=> '^logout(/.*)$',
			'vars'		=> 'controller=User&action=logout&redirect=$1',
			'url'		=> 'logout{redirect}'
		),
		
		
		// Auto completion
		'autocompletion_student_name'	=> array(
			'regexp'	=> '^ajax/autocomplete/student/name(?=\?|$)',
			'vars'		=> 'controller=Student&action=autocomplete&mode=json',
			'url'		=> 'ajax/autocomplete/student/name'
		),
		
	);

}
