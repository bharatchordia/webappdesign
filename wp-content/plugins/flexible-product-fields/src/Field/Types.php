<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Field;

use WPDesk\FPF\Free\Field\Type\TypeIntegration;
use WPDesk\FPF\Free\Field\Type\TextType;
use WPDesk\FPF\Free\Field\Type\TextareaType;
use WPDesk\FPF\Free\Field\Type\NumberType;
use WPDesk\FPF\Free\Field\Type\SelectType;
use WPDesk\FPF\Free\Field\Type\MultiselectType;
use WPDesk\FPF\Free\Field\Type\RadioType;
use WPDesk\FPF\Free\Field\Type\RadioImagesType;
use WPDesk\FPF\Free\Field\Type\CheckboxType;
use WPDesk\FPF\Free\Field\Type\HeadingType;
use WPDesk\FPF\Free\Field\Type\DateType;

/**
 * Supports management of field types.
 */
class Types {

	/**
	 * Initializes actions for class.
	 *
	 * @return void
	 */
	public function init() {
		( new TypeIntegration( new TextType() ) )->hooks();
		( new TypeIntegration( new TextareaType() ) )->hooks();
		( new TypeIntegration( new NumberType() ) )->hooks();
		( new TypeIntegration( new SelectType() ) )->hooks();
		( new TypeIntegration( new MultiselectType() ) )->hooks();
		( new TypeIntegration( new RadioType() ) )->hooks();
		( new TypeIntegration( new RadioImagesType() ) )->hooks();
		( new TypeIntegration( new CheckboxType() ) )->hooks();
		( new TypeIntegration( new HeadingType() ) )->hooks();
		( new TypeIntegration( new DateType() ) )->hooks();
	}
}
