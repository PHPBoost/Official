<?php

/**
 * Type that maps a database SMALLINT to a PHP integer.
 *
 * @author robo
 */
class SmallIntType
{
    public function getName()
    {
        return "SmallInteger";
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getSmallIntTypeDeclarationSql($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (int) $value;
    }

    public function getTypeCode()
    {
    	return self::CODE_INT;
    }
}