<?php

use Illuminate\Database\Seeder;
use App\RequestAttribute;

class RequestAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        	[
        		'name' => 'Sensor',
        		'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:{name}-id" DataType="http://www.w3.org/2001/XMLSchema#{data-type}">
					            	<AttributeValue>{{name}-value}</AttributeValue>
					        	</Attribute>'
        	],
        	[
        		'name' => 'Time',
        		'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:time-id" DataType="http://www.w3.org/2001/XMLSchema#time">
					            	<AttributeValue>{time-value}</AttributeValue>
					        	</Attribute>'
        	],
            [
                'name' => 'Date',
                'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:date-id" DataType="http://www.w3.org/2001/XMLSchema#date">
                                    <AttributeValue>{date-value}</AttributeValue>
                                </Attribute>'
            ],
            [
                'name' => 'Location',
                'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:location-id" DataType="http://www.w3.org/2001/XMLSchema#string">
                                    <AttributeValue>{location-value}</AttributeValue>
                                </Attribute>'
            ],
            [
                'name' => 'User ID',
                'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:user-id" DataType="http://www.w3.org/2001/XMLSchema#string">
                                    <AttributeValue>{user-id-value}</AttributeValue>
                                </Attribute>'
            ],
            [
                'name' => 'Device IP Address',
                'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:device-ip-address-id" DataType="http://www.w3.org/2001/XMLSchema#string">
                                    <AttributeValue>{device-ip-address-value}</AttributeValue>
                                </Attribute>'
            ],
            [
                'name' => 'Risk Acessment/History',
                'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:risk-acessment-history-id" DataType="http://www.w3.org/2001/XMLSchema#double">
                                    <AttributeValue>{risk-accessment-history-value}</AttributeValue>
                                </Attribute>'
            ],
            [
                'name' => 'Risk Acessment/Location',
                'operation' => '<Attribute AttributeId="urn:oasis:names:tc:xacml:1.0:environment:risk-acessment-location-id" DataType="http://www.w3.org/2001/XMLSchema#double">
                                    <AttributeValue>{risk-accessment-location-value}</AttributeValue>
                                </Attribute>'
            ],
        ];

        foreach($data as $d){
            RequestAttribute::create($d);
        }
    }
}
