<script lang="ts">
    import type Product from '../interfaces/products';
    export let product: Product;
    import {goto} from '$app/navigation'
    import { onMount } from 'svelte';
    import { filterProductsByCategory } from '../api/products';
    import ProductCard from './ProductCard.svelte';

    let similarProducts: Product[] = [];

    onMount(async () => {
        try {
            const response = await filterProductsByCategory(product.category);

            similarProducts = response.products;
            similarProducts = similarProducts.filter(prod => prod.id !== product.id);
        } catch (error) {
            console.error("Error at sending the request to the server: " + error);
        }
    });

    const handleNavigation = (name: string) => {
        goto(`/products/${name}`);
    }

    const handleKey = () => {}
</script>

<div class="block w-full py-10 my-10">
    <div class="flex justify-between">
        <div class="block w-2/3 mx-6 p-6 bg-white ">
            <h1 class="text-lg font-semibold">{product.name}</h1>
    
            <img 
                src={product.photoUrl} 
                alt="product_data"
            >
        </div>
    
        <div class="block justify-center items-center w-1/2 shadow-lg rounded-md mx-auto ">
            <h1 class="font-bold text-red-600 text-lg m-4">{product.price}$</h1>
            {#if product.quantity <= 0}
                <h6 class="text-red-600 font-sans text-lg mx-4">Out of stock</h6>
            {:else if product.quantity > 0 && product.quantity <= 5}
                <h6 class="text-black font-sans text-lg mx-4">{product.quantity} left</h6>
            {:else}
                <h6 class="text-black font-sans text-lg mx-4">In stock</h6>
            {/if}
            <div class="flex justify-center text-center items-center mx-auto my-4">
                <button class="w-full bg-blue-500 hover:bg-blue-600 rounded-md text-white text-lg font-semibold  py-2 mx-8">Add to cart</button>
            </div>
        </div>
    </div>



    <div class="container p-4 bg-gray-50 mx-6">
        <h1 class="text-2xl font-bold mb-4">Similar products: </h1>
        
        <div class="mx-auto grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {#each similarProducts as product}
                <ProductCard {product} />
            {/each}
        </div>
    </div>
</div>