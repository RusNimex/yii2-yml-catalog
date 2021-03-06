<?php
namespace pastuhov\ymlcatalog\models;

class SimpleOffer extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static $tag = 'offer';
    /**
     * @inheritdoc
     */
    public static $tagProperties = [
        'id',
        'bid',
        'cbid',
        'available'
    ];

    public $id;
    public $bid;
    public $cbid;
    public $available;

    public $url;
    public $price;
    public $oldprice;
    public $currencyId;
    public $categoryId;
    public $market_Category;
    public $store;
    public $pickup;
    public $delivery;
    public $local_Delivery_Cost;
    public $name;
    public $vendor;
    public $vendorCode;
    public $description;
    public $sales_notes;
    public $manufacturer_Warranty;
    public $country_Of_Origin;
    public $adult;
    public $age;
    public $barcode;
    public $cpa;
    public $params = [];
    public $pictures = [];

    /**
     * @inheritdoc
     */
    public function getYmlAttributes()
    {
        return [
            'url',
            'price',
            'oldprice',
            'currencyId',
            'categoryId',
            'market_Category',
            'store',
            'pickup',
            'delivery',
            'local_Delivery_Cost',
            'name',
            'vendor',
            'vendorCode',
            'description',
            'sales_notes',
            'manufacturer_Warranty',
            'country_Of_Origin',
            'adult',
            'age',
            'barcode',
            'cpa',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['id', 'price', 'currencyId', 'categoryId', 'name', 'available'],
                'required',
            ],
            [
                ['sales_notes'],
                'string',
                'max' => 50,
            ],
            [
                ['name', 'vendor'],
                'string',
                'max' => 120,
            ],
            [
                ['delivery', 'pickup'],
                'string',
                'max' => 4,
            ],
            [
                ['id', 'categoryId', 'bid', 'cbid'],
                'integer',
            ],
            [
                ['name', 'market_Category'],
                'string',
            ],
            [
                ['url'],
                'url',
            ],
            [
                ['price', 'oldprice'],
                'double',
            ],
            [
                [
                    'currencyId',
                ],
                'in',
                'range' => [
                    'RUR',
                    'RUB',
                    'UAH',
                    'BYR',
                    'KZT',
                    'USD',
                    'EUR'
                ],
            ],
            [
                ['description'],
                'string',
            ],
            [
                ['pictures'],
                function ($attribute, $params) {
                    if (count($this->pictures) > 10) {
                        $this->addError('pictures', 'maximum 10 pictures');
                    }
                }
            ],
            [
                ['params'],
                'each',
                'rule' => ['string']
            ],
            [
                ['pictures'],
                'each',
                'rule' => ['url']
            ]
        ];
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param array $pictures
     */
    public function setPictures(array $pictures)
    {
        $this->pictures = $pictures;
    }

    /**
     * @inheritdoc
     */
    protected function getYmlBody()
    {
        $string = '';

        foreach ($this->getYmlAttributes() as $attribute) {
            $string .= $this->getYmlAttribute($attribute);
        }

        foreach ($this->params as $name => $value) {
            $string .= $this->getYmlParamTag($name, $value);
        }

        foreach ($this->pictures as $picture) {
            $string .= $this->getYmlPictureTag($picture);
        }

        return $string;
    }


    /**
     * @param string $attribute
     * @param string $value
     * @return string
     */
    protected function getYmlParamTag($attribute, $value)
    {
        if ($value === null) {
            return '';
        }

        $string = '<param name="' . $attribute . '">' . $value . '</param>' . PHP_EOL;

        return $string;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function getYmlPictureTag($value)
    {
        if ($value === null) {
            return '';
        }

        $string = '<picture>' . $value . '</picture>' . PHP_EOL;

        return $string;
    }
}
