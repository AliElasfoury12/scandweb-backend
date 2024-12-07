Declration :
    the backend is a Small Framwork i Wrote Line by Line 


usage :

    Migrations
        php bmbo migrate

    Seed the Database
        php bmbo seed
    
    Create New Files
        php bmbo migrations create_TableName_table // new migration for creating table
        php bmbo model modelName // new model
        php bmbo controller controllerName // new Controller
    
GraphQL :

    Products Query :

        query {
            products{
                id
                name
                inStock
                category
                brand
                __typename
                gallery 
                attributes {
                    id
                    name
                    type
                    __typename
                    items{
                        id
                        displayValue
                        value
                        __typename
                    }
                }
                prices {
                    amount
                    currency
                    __typename
                }
            }
        }

    Product Query :

    query {
        product (id: "xbox-series-s"){
            id
            name
            inStock
            category
            brand
            __typename
            gallery {
                img
            }
            prices {
                amount
                currency
                __typename
            }
            attributes {
                id
                name
                type
                __typename
                items{
                    id
                    displayValue
                    value
                    __typename
                }
            }
        }
    }

    Categories Query :

    query{
        getCategories{
            min
            categories{
                    name
                __typename
            }
        }
    }

    Order Mutation :

        mutation ($products: [OrderItemInput!]!) {
            order(products: $products) {
                id
                products {
                    product_id
                    attributes
                    quantity
                }
            }
        }