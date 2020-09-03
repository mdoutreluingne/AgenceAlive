<?php 
namespace mdoutreluingne\RecaptchaBundle\Type;

use mdoutreluingne\RecaptchaBundle\Constraints\Recaptcha;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RecaptchaSubmitType extends AbstractType
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
            'constraints' => new Recaptcha()
        ]);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label'] = false;
        $view->vars['key'] = $this->key;
        $view->vars['button'] = $options['label'];
    }

    public function getBlockPrefix()
    {
        return 'recaptcha_submit';
    }

    public function getParent()
    {
        return TextType::class;
    }
}
?>