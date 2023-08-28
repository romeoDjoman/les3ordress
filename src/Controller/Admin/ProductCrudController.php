<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nameProduct'),
            TextareaField::new('description'),
            TextareaField::new('moreInformations'),
            DateTimeField::new('createdAt')->hideOnForm(),
            MoneyField::new('priceProduct', 'Prix')->setCurrency('EUR'),
            BooleanField::new('isBest', 'Les Meilleurs'),
            BooleanField::new('isNew', 'Nouveautés'),
            AssociationField::new('category'),
            SlugField::new('slug')->setTargetFieldName('nameProduct'),
            ImageField::new('imageProduct')->setBasePath('assets/upload/products/')
                                            ->setUploadDir('public/assets/upload/products/')
                                            ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
    
}
