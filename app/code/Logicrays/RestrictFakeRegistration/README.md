# About  Extension
Magento 2 Restrick Fake Registration is restric by dummy user and email.

# Related GraphQL
1) type StoreConfig

    type StoreConfig {
        restrictfakeregistration_general_enable : String @doc(description: "fetch value of module enable or disable"),
    restrictfakeregistration_general_restriction_type : String @doc(description: "fetch value of domain restirct type"),
    restrictfakeregistration_general_domains_list : String @doc(description: "fetch value of domain list")
    }

-> This graphql returns all of the admin-side configuration values.
