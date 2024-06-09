import type Product from "../interfaces/products";

export const getAllProducts = async() => {
    try {
        const response = await fetch("http://localhost:8080/products", {
            method: 'GET',
            headers: {
                'Accept': 'text/html',
            },
        });

        if (!response.ok) {
            throw new Error("Failed to fetch products");
        }

        if (response === undefined || response === null) {
            throw new Error("No products were fetched from the server");
        }

        return response.json();
    } catch (error) {
        console.error("Failed to fetch all products from the server: " + error);
    }
}

export const getProductByName = async(name: string) => {
    if (name === undefined || name === null) {
        throw new Error("Failed to search for the product based on its name");
    }

    try {
        const response = await fetch(`http://localhost:8080/products/${name}`, {
            headers: {
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error("Failed to fetch product data from the server");
        }
        
        return response.json();
    } catch (error) {
        console.error("Failed to search for the product based on its name");
    }
}

export const filterProductsByCategory = async(category: string) => {
    if (category === undefined || category === null) {
        throw new Error("Failed to search for products based on its category");
    }

    try {
        const response = await fetch(`http://localhost:8080/filtered-products?category=${encodeURIComponent(category)}`, {
            headers: {
                'Accept': 'application/json'
            },
        });

        if (!response.ok) {
            throw new Error("Failed to fetch products from the server");
        }

        return response.json();
    } catch (error) {
        console.error("Failed to search for products based on its category");
    }
}

export const createProduct = async (product: Product) => {
    if (!product) {
        throw new Error("No product data was provided");
    }

    try {
        console.log(product);
        const response = await fetch("http://localhost:8080/products", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(product),  
              
        });

        if (!response.ok) {
            throw new Error("Error creating the product");
        }

        return response.json();
    } catch (error) {
        console.error("Failed to create product");
    }
}

export const deleteProduct = async (productId: number) => {
    try {
        const response = await fetch(`http://localhost:8080/products/${productId}`, {
            method: "DELETE",
            headers: {
                'Content-Type': 'application/json',
            },
        })

        if (response.ok)
            return response.json();
    } catch (err) {
        console.error(err);
    }
}
