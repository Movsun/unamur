<?php

use Illuminate\Database\Seeder;
use App\PolicyAttribute;

class PolicyAttributesTableSeeder extends Seeder
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
            	'name' => 'Time',
            	'operation' => [
                    [
                       'name' => 'in-between',
            	       'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:and">
                    				<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:time-greater-than-or-equal">
                                        <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:time-one-and-only">
                    						<EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:time-id" DataType="http://www.w3.org/2001/XMLSchema#time"/>
                    					</Apply>
                    					<AttributeValue DataType="http://www.w3.org/2001/XMLSchema#time">{1st-value}</AttributeValue>
                    				</Apply>
                    				<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:time-less-than-or-equal">
                    					<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:time-one-and-only">
                    						<EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:time-id" DataType="http://www.w3.org/2001/XMLSchema#time"/>
                    					</Apply>
                    					<AttributeValue DataType="http://www.w3.org/2001/XMLSchema#time">{2nd-value}</AttributeValue>
                    				</Apply>
                                </Apply>'
                    ]
                ],
            ],
            [
                'name' => 'Date',
                'operation' => [
                    [
                       'name' => 'in-between',
                       'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:and">
                                    <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:date-greater-than-or-equal">
                                        <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:date-one-and-only">
                                            <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:date-id" DataType="http://www.w3.org/2001/XMLSchema#date"/>
                                        </Apply>
                                        <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#date">{1st-value}</AttributeValue>
                                    </Apply>
                                    <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:date-less-than-or-equal">
                                        <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:date-one-and-only">
                                            <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:date-id" DataType="http://www.w3.org/2001/XMLSchema#date"/>
                                        </Apply>
                                        <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#date">{2nd-value}</AttributeValue>
                                    </Apply>
                                </Apply>'
                    ]
                ],
            ],
            [
                'name' => 'Sensor',
                'operation' => [
                    [
                        'name' => '=float', 
                        'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-equal">
                                        <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-one-and-only">
                                            <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:{name}-id" DataType="http://www.w3.org/2001/XMLSchema#double"/>
                                        </Apply>
                                        <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#double">{value}</AttributeValue>
                                    </Apply>'
                    ],
                    [
                        'name' => '>float', 
                        'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-greater-than">
                                        <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-one-and-only">
                                            <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:{name}-id" DataType="http://www.w3.org/2001/XMLSchema#double"/>
                                        </Apply>
                                        <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#double">{value}</AttributeValue>
                                    </Apply>'
                    ],
                    [
                        'name' => '<float', 
                        'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-less-than">
                                        <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-one-and-only">
                                            <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:{name}-id" DataType="http://www.w3.org/2001/XMLSchema#double"/>
                                        </Apply>
                                        <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#double">{value}</AttributeValue>
                                    </Apply>'
                    ],
                    [
                        'name' => '=string', 
                        'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-equal">
                                        <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-one-and-only">
                                            <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:{name}-id" DataType="http://www.w3.org/2001/XMLSchema#string"/>
                                        </Apply>
                                        <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#string">{value}</AttributeValue>
                                    </Apply>'
                    ],
                ],
            ],
            [
                'name' => 'Location',
                'operation' => [
                    [
                       'name' => 'equal',
                       'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-equal">
                                <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-one-and-only">
                                    <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:location-id" DataType="http://www.w3.org/2001/XMLSchema#string"/>
                                </Apply>
                                <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#string">{value}</AttributeValue>
                                </Apply>'
                    ]
                ],
            ],
            [
                'name' => 'Device IP Address',
                'operation' => [
                    [
                       'name' => 'equal',
                       'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-equal">
                                <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-one-and-only">
                                    <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:device-ip-address-id" DataType="http://www.w3.org/2001/XMLSchema#string"/>
                                </Apply>
                                <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#string">{value}</AttributeValue>
                                </Apply>'
                    ]
                ],
            ],
            [
                'name' => 'User ID',
                'operation' => [
                    [
                       'name' => 'equal',
                       'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-equal">
                                <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:string-one-and-only">
                                    <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:user-id" DataType="http://www.w3.org/2001/XMLSchema#string"/>
                                </Apply>
                                <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#string">{value}</AttributeValue>
                                </Apply>'
                    ]
                ],
            ],
            [
                'name' => 'Risk Acessment/History',
                'operation' => [
                    [
                       'name' => 'equal',
                       'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-greater-than-or-equal">
                                <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-one-and-only">
                                    <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:risk-acessment-history-id" DataType="http://www.w3.org/2001/XMLSchema#double"/>
                                </Apply>
                                <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#double">{value}</AttributeValue>
                                </Apply>'
                    ]
                ],
            ],
            [
                'name' => 'Risk Acessment/Location',
                'operation' => [
                    [
                       'name' => 'equal',
                       'xml' => '<Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-greater-than-or-equal">
                                <Apply FunctionId="urn:oasis:names:tc:xacml:1.0:function:double-one-and-only">
                                    <EnvironmentAttributeDesignator AttributeId="urn:oasis:names:tc:xacml:1.0:environment:risk-acessment-location-id" DataType="http://www.w3.org/2001/XMLSchema#double"/>
                                </Apply>
                                <AttributeValue DataType="http://www.w3.org/2001/XMLSchema#double">{value}</AttributeValue>
                                </Apply>'
                    ]
                ],
            ],
        ];


        foreach ($data as $d){
            PolicyAttribute::create([
                    'name' => $d['name'],
                    'operation' => json_encode($d['operation'])
                ]);
        }
    }
}
