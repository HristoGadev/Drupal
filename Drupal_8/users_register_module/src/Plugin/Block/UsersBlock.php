<?php
/**
 * Created by PhpStorm.
 * User: hr_ga
 * Date: 5/5/2019
 * Time: 10:46 AM
 */
namespace Drupal\users_register_module\Plugin\Block;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Users' Block
 *
 * @Block(
 *   id = "users_block",
 *   admin_label = @Translation("Hello"),
 * )
 */

class UsersBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */

    public function build()
    {
        $form = \Drupal::formBuilder()->getForm('Drupal\users_register_module\Form\RegisterForm');
        return $form;
    }
}