<?php

namespace app\controllers;

use app\models\Categorie;
use app\models\Product;
use GraphQL\GraphQL ;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use RuntimeException;
use Throwable;

class GraphQLController extends Controller {
    static public function handle() {
        try {

            $pricesType = new ObjectType([
                'name' => 'Prices',
                'fields' => [
                    'amount' => ['type' => Type::string()],
                    'currency' => ['type' => Type::string()],
                    '__typename' => ['type' => Type::string()],
                ]
            ]);

            $itemsType = new ObjectType([
                'name' => 'Items',
                'fields' => [
                    'id' => ['type' => Type::string()],
                    'value' => ['type' => Type::string()],
                    'displayValue' => ['type' => Type::string()],
                    '__typename' => ['type' => Type::string()]
                ],
            ]);

            $attributesType = new ObjectType([
                'name' => 'Attributes',
                'fields' => [
                    'id' => ['type' => Type::string()],
                    'name' => ['type' => Type::string()],
                    'items' => ['type' => Type::listOf($itemsType)],
                    'type' => ['type' => Type::string()],
                    '__typename' => ['type' => Type::string()],
                ],
            ]);

            $galleryType = new ObjectType([
                'name' => 'Gallery',
                'fields' => [
                    'img' => ['type' => Type::string()]
                ]
            ]);

            $categoryType = new ObjectType([
                'name' => 'Category',
                'fields' => [
                    'name' => ['type' => Type::string()],
                    '__typename' => ['type' => Type::string()]
                ]
            ]);

            $categoriesType = new ObjectType([
                'name' => 'Categories',
                'fields' => [
                    'min' => ['type' => Type::string()],
                    'categories' => ['type' => Type::listOf($categoryType)]
                ]
            ]);

            $productsType = new ObjectType([
                'name' => 'Products',
                'fields' => [
                    'id' => ['type' => Type::string()],
                    'name' => ['type' => Type::string()],
                    'inStock' => ['type' => Type::string()],
                    'category' => ['type' => Type::string()],
                    'brand' => ['type' => Type::string()],
                    'description' => ['type' => Type::string()],
                    '__typename' => ['type' => Type::string()],
                    'gallery' => ['type' => Type::string()],
                    'attributes' => ['type' => Type::listOf($attributesType)],
                    'prices' => ['type' => $pricesType],
                ],
            ]);

            $productType = new ObjectType([
                'name' => 'Product',
                'fields' => [
                    'id' => ['type' => Type::string()],
                    'name' => ['type' => Type::string()],
                    'inStock' => ['type' => Type::string()],
                    'category' => ['type' => Type::string()],
                    'brand' => ['type' => Type::string()],
                    'description' => ['type' => Type::string()],
                    '__typename' => ['type' => Type::string()],
                    'gallery' => ['type' => Type::listOf($galleryType)],
                    'attributes' => ['type' => Type::listOf($attributesType)],
                    'prices' => ['type' => $pricesType],
                ],
            ]);
        
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'getCategories' => [
                        'type' => $categoriesType,
                        'resolve' => static function ($r, $a, $c, ResolveInfo $resolveInfo )
                        {
                            $fields = $resolveInfo->getFieldSelection(1);
                            $fields = array_keys($fields['categories']);
                            $min = Product::minRepeat('category');
                            $categories = Categorie::all($fields);
                            return ['categories' => $categories, 'min' => $min];
                        } 
                    ],
                    'products' => [
                        'type' => Type::listOf($productsType),
                        'resolve' => static function ($r, $a, $c, ResolveInfo $resolveInfo )
                        {
                            return ProductsController::handle($resolveInfo);
                        } 
                    ],
                    'product' => [
                        'type' => Type::listOf($productType),
                        'args' => [
                            'id' => ['type' => Type::string()],
                        ],
                        'resolve' => static function ($r, $args, $c, ResolveInfo $resolveInfo ){
                            return ProductController::handle($resolveInfo, $args['id']);
                        } 
                    ]
                ]
            ]);

            #Mutation ///////////////////////////////////////////////////////////////////////////////////////////////////

            $orderItemType = new ObjectType([
                'name' => 'OrderItem',
                'fields' => [
                    'product_id' => ['type' => Type::id()],
                    'attributes' => ['type' => Type::string()],
                    'quantity' => ['type' => Type::int()],
                ],
            ]);

            $orderType = new ObjectType([
                'name' => 'Order',
                'fields' => [
                    'id' => ['type' => Type::id()],
                    'products' => ['type' => Type::listOf($orderItemType)]
                ]
            ]);

            $orderItemInputType = new InputObjectType([
                'name' => 'OrderItemInput',
                'fields' => [
                    'product_id' => ['type' => Type::id()],
                    'attributes' => ['type' => Type::string()], 
                    'quantity' => ['type' => Type::int()],
                ]
            ]);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'order' => [
                        'type' => $orderType,
                        'args' => [
                            'products' => ['type' => Type::listOf($orderItemInputType)],
                        ],
                        'resolve' => fn($c, $args) => OrderController::handle($args)
                    ]
                ]
            ]);
         
        
            // See docs on schema options:
            // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
            $schema = new Schema([
                'query' => $queryType,
                'mutation' => $mutationType,
            ]);
        
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }
        
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;
        
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();

        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output, JSON_PRETTY_PRINT);
    }
}