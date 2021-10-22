<?php

namespace App\Controller\Admin;

use App\Entity\Board;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BoardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Board::class;
    }

/*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextField::new('author'),
            TextField::new('game'),
            TextEditorField::new('description'),
            DateField::new('date'),
            BooleanField::new('full'),
            BooleanField::new('online'),
            AssociationField::new('category'),
            AssociationField::new('users')
            
        ];
    }
*/
}
