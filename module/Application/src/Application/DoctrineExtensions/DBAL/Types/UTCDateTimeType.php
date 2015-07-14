<?php
namespace Application\DoctrineExtensions\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
/**
 * Based on :
 *  
 * @see http://doctrine-orm.readthedocs.org/en/latest/cookbook/working-with-datetime.html
 * 
 * But with some bugs fixed :
 * 
 * @see https://github.com/braincrafted/doctrine-bundle/blob/master/DBAL/Type/UTCDateTimeType.php
 * 
 */
class UTCDateTimeType extends DateTimeType
{
    /** @var \DateTimeZone */
    static private $utc = null;
    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if (!$value instanceof \DateTime) {
            return null;
        }
        $value->setTimezone((self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC')));
        return $value->format($platform->getDateTimeFormatString());
    }
    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        $val = \DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            (self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC'))
        );
        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }
}