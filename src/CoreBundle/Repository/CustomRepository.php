<?php

namespace CoreBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use DoctrineExtensions\Query\Mysql\Acos;
use DoctrineExtensions\Query\Mysql\AesDecrypt;
use DoctrineExtensions\Query\Mysql\AesEncrypt;
use DoctrineExtensions\Query\Mysql\AnyValue;
use DoctrineExtensions\Query\Mysql\Ascii;
use DoctrineExtensions\Query\Mysql\Asin;
use DoctrineExtensions\Query\Mysql\Atan;
use DoctrineExtensions\Query\Mysql\Atan2;
use DoctrineExtensions\Query\Mysql\Binary;
use DoctrineExtensions\Query\Mysql\BitCount;
use DoctrineExtensions\Query\Mysql\BitXor;
use DoctrineExtensions\Query\Mysql\Ceil;
use DoctrineExtensions\Query\Mysql\CharLength;
use DoctrineExtensions\Query\Mysql\Collate;
use DoctrineExtensions\Query\Mysql\ConcatWs;
use DoctrineExtensions\Query\Mysql\ConvertTz;
use DoctrineExtensions\Query\Mysql\Cos;
use DoctrineExtensions\Query\Mysql\Cot;
use DoctrineExtensions\Query\Mysql\CountIf;
use DoctrineExtensions\Query\Mysql\Crc32;
use DoctrineExtensions\Query\Mysql\Date;
use DoctrineExtensions\Query\Mysql\DateAdd;
use DoctrineExtensions\Query\Mysql\DateDiff;
use DoctrineExtensions\Query\Mysql\DateFormat;
use DoctrineExtensions\Query\Mysql\DateSub;
use DoctrineExtensions\Query\Mysql\Day;
use DoctrineExtensions\Query\Mysql\DayName;
use DoctrineExtensions\Query\Mysql\DayOfWeek;
use DoctrineExtensions\Query\Mysql\DayOfYear;
use DoctrineExtensions\Query\Mysql\Degrees;
use DoctrineExtensions\Query\Mysql\Extract;
use DoctrineExtensions\Query\Mysql\Field;
use DoctrineExtensions\Query\Mysql\FindInSet;
use DoctrineExtensions\Query\Mysql\Floor;
use DoctrineExtensions\Query\Mysql\FromUnixtime;
use DoctrineExtensions\Query\Mysql\Greatest;
use DoctrineExtensions\Query\Mysql\GroupConcat;
use DoctrineExtensions\Query\Mysql\Hex;
use DoctrineExtensions\Query\Mysql\IfElse;
use DoctrineExtensions\Query\Mysql\IfNull;
use DoctrineExtensions\Query\Mysql\LastDay;
use DoctrineExtensions\Query\Mysql\Least;
use DoctrineExtensions\Query\Mysql\Log10;
use DoctrineExtensions\Query\Mysql\Lpad;
use DoctrineExtensions\Query\Mysql\MatchAgainst;
use DoctrineExtensions\Query\Mysql\Md5;
use DoctrineExtensions\Query\Mysql\Minute;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\MonthName;
use DoctrineExtensions\Query\Mysql\Now;
use DoctrineExtensions\Query\Mysql\NullIf;
use DoctrineExtensions\Query\Mysql\Pi;
use DoctrineExtensions\Query\Mysql\Power;
use DoctrineExtensions\Query\Mysql\Quarter;
use DoctrineExtensions\Query\Mysql\Radians;
use DoctrineExtensions\Query\Mysql\Rand;
use DoctrineExtensions\Query\Mysql\Regexp;
use DoctrineExtensions\Query\Mysql\Replace;
use DoctrineExtensions\Query\Mysql\Round;
use DoctrineExtensions\Query\Mysql\Rpad;
use DoctrineExtensions\Query\Mysql\Second;
use DoctrineExtensions\Query\Mysql\Sha1;
use DoctrineExtensions\Query\Mysql\Sha2;
use DoctrineExtensions\Query\Mysql\Sin;
use DoctrineExtensions\Query\Mysql\Soundex;
use DoctrineExtensions\Query\Mysql\Std;
use DoctrineExtensions\Query\Mysql\StdDev;
use DoctrineExtensions\Query\Mysql\StrToDate;
use DoctrineExtensions\Query\Mysql\SubstringIndex;
use DoctrineExtensions\Query\Mysql\Tan;
use DoctrineExtensions\Query\Mysql\Time;
use DoctrineExtensions\Query\Mysql\TimeDiff;
use DoctrineExtensions\Query\Mysql\TimestampAdd;
use DoctrineExtensions\Query\Mysql\TimestampDiff;
use DoctrineExtensions\Query\Mysql\TimeToSec;
use DoctrineExtensions\Query\Mysql\Unhex;
use DoctrineExtensions\Query\Mysql\UnixTimestamp;
use DoctrineExtensions\Query\Mysql\UtcTimestamp;
use DoctrineExtensions\Query\Mysql\UuidShort;
use DoctrineExtensions\Query\Mysql\Variance;
use DoctrineExtensions\Query\Mysql\Week;
use DoctrineExtensions\Query\Mysql\WeekDay;
use DoctrineExtensions\Query\Mysql\Year;
use DoctrineExtensions\Query\Mysql\YearWeek;

/**
 * CustomRepository
 *
 * Created By Abdelhak ouaddi abdelhak.ouaddi@gmail.com
 */
class CustomRepository extends \Doctrine\ORM\EntityRepository
{
     public function __construct(EntityManager $em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

        /* Ajouter Mysql function to DQL */
        
           $this->_em->getConfiguration()->addCustomDatetimeFunction('year',Year::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('month',Month::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('day',Day::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('convert_tz',ConvertTz::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('date',Date::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('date_format',DateFormat::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('dateadd',DateAdd::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('datesub',DateSub::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('datediff',DateDiff::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('dayname',DayName::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('dayofweek',DayOfWeek::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('dayofyear',DayOfYear::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('from_unixtime',FromUnixtime::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('last_day',LastDay::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('minute',Minute::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('now',Now::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('monthname',MonthName::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('second',Second::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('strtodate',StrToDate::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('time',Time::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('timediff',TimeDiff::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('timestampadd',TimestampAdd::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('timestampdiff',TimestampDiff::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('timetosec',TimeToSec::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('week',Week::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('weekday',WeekDay::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('yearweek',YearWeek::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('unix_timestamp',UnixTimestamp::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('utc_timestamp',UtcTimestamp::class);
            $this->_em->getConfiguration()->addCustomDatetimeFunction('extract',Extract::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('acos',Acos::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('asin',Asin::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('atan2',Atan2::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('atan',Atan::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('bit_count',BitCount::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('bit_xor',BitXor::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('ceil',Ceil::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('cos',Cos::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('cot',Cot::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('degrees',Degrees::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('floor',Floor::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('log10',Log10::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('pi',Pi::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('power',Power::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('quarter',Quarter::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('radians',Radians::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('rand',Rand::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('round',Round::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('stddev',StdDev::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('sin',Sin::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('std',Std::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('tan',Tan::class);
            $this->_em->getConfiguration()->addCustomNumericFunction('variance',Variance::class);
            $this->_em->getConfiguration()->addCustomStringFunction('aes_decrypt',AesDecrypt::class);
            $this->_em->getConfiguration()->addCustomStringFunction('aes_encrypt',AesEncrypt::class);
            $this->_em->getConfiguration()->addCustomStringFunction('any_value',AnyValue::class);
            $this->_em->getConfiguration()->addCustomStringFunction('ascii',Ascii::class);
            $this->_em->getConfiguration()->addCustomStringFunction('binary',Binary::class);
            $this->_em->getConfiguration()->addCustomStringFunction('char_length',CharLength::class);
            $this->_em->getConfiguration()->addCustomStringFunction('collate',Collate::class);
            $this->_em->getConfiguration()->addCustomStringFunction('concat_ws',ConcatWs::class);
            $this->_em->getConfiguration()->addCustomStringFunction('countif',CountIf::class);
            $this->_em->getConfiguration()->addCustomStringFunction('crc32',Crc32::class);
            $this->_em->getConfiguration()->addCustomStringFunction('degrees',Degrees::class);
            $this->_em->getConfiguration()->addCustomStringFunction('field',Field::class);
            $this->_em->getConfiguration()->addCustomStringFunction('find_in_set',FindInSet::class);
            $this->_em->getConfiguration()->addCustomStringFunction('greatest',Greatest::class);
            $this->_em->getConfiguration()->addCustomStringFunction('group_concat',GroupConcat::class);
            $this->_em->getConfiguration()->addCustomStringFunction('ifelse',IfElse::class);
            $this->_em->getConfiguration()->addCustomStringFunction('ifnull',IfNull::class);
            $this->_em->getConfiguration()->addCustomStringFunction('least',Least::class);
            $this->_em->getConfiguration()->addCustomStringFunction('lpad',Lpad::class);
            $this->_em->getConfiguration()->addCustomStringFunction('match',MatchAgainst::class);
            $this->_em->getConfiguration()->addCustomStringFunction('md5',Md5::class);
            $this->_em->getConfiguration()->addCustomStringFunction('nullif',NullIf::class);
            $this->_em->getConfiguration()->addCustomStringFunction('radians',Radians::class);
            $this->_em->getConfiguration()->addCustomStringFunction('regexp',Regexp::class);
            $this->_em->getConfiguration()->addCustomStringFunction('replace',Replace::class);
            $this->_em->getConfiguration()->addCustomStringFunction('rpad',Rpad::class);
            $this->_em->getConfiguration()->addCustomStringFunction('sha1',Sha1::class);
            $this->_em->getConfiguration()->addCustomStringFunction('sha2',Sha2::class);
            $this->_em->getConfiguration()->addCustomStringFunction('soundex',Soundex::class);
            $this->_em->getConfiguration()->addCustomStringFunction('str_to_date',StrToDate::class);
            $this->_em->getConfiguration()->addCustomStringFunction('substring_index',SubstringIndex::class);
            $this->_em->getConfiguration()->addCustomStringFunction('uuid_short',UuidShort::class);
            $this->_em->getConfiguration()->addCustomStringFunction('hex',Hex::class);
            $this->_em->getConfiguration()->addCustomStringFunction('unhex',Unhex::class);
        
    }


}
