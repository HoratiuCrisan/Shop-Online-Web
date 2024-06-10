<script lang="ts">
    import type Product from '../interfaces/products';
    
    export let product: Product;

    import { goto } from '$app/navigation';
    import { onMount, onDestroy } from 'svelte';
    import { filterProductsByCategory } from '../api/products';
    import ProductCard from './ProductCard.svelte';
    import ProductDelete from './ProductDelete.svelte';
    import { writable } from 'svelte/store';
    import UpdateProductDialog from './UpdateProductDialog.svelte';

    let selectedQuantity = 1;

    const showDeleteDialog = writable(false);
    const productId = product.id;

    console.log(product);

    const showUpdateDialog = writable(false);

    const openDeleteDialog = () => {
        showDeleteDialog.set(true);
    }

    const openUpdateDialog = () => {
        showUpdateDialog.set(true);
    }

    let similarProducts: Product[] = [];

    const fetchSimilarProducts = async () => {
        try {
            console.log("Fetching");
            const response = await filterProductsByCategory(product.category);
            similarProducts = response.products.filter(prod => prod.name !== product.name);
            similarProducts = similarProducts.slice(0, 4);
        } catch (error) {
            console.error("Error sending the request to the server: " + error);
        }
    };

    // Fetch similar products on component mount
    onMount(fetchSimilarProducts);

    // Fetch similar products whenever the product changes
    $: {
        fetchSimilarProducts();
    }

    onDestroy(() => {
        similarProducts = []; // Clear similar products on component destroy
    });

    const handleNavigation = (name: string) => {
        goto(`/products/${name}`);
    }

    const handleKey = () => {}

    const increaseQuantity = () => {
        if (selectedQuantity < product.quantity) {
            selectedQuantity += 1;
        }
    }

    const decreaseQuantity = () => {
        if (selectedQuantity > 1) {
            selectedQuantity -= 1;
        }
    }
</script>

<div class="block w-full py-10 my-10">
    <div class="block md:flex justify-between">
        <div class="block w-2/4 mx-6 p-6 bg-white ">
            <h1 class="text-lg font-semibold mb-4">{product.name}</h1>
    
           <div class="flex justify-center items-center mx-auto ">
                <img 
                    src={product.photoUrl} 
                    alt="product_data"
                    width="300vh"
                    height="100vh"
                >
           </div>
        </div>
    
        <div class="block justify-center items-center w-1/2 shadow-lg rounded-md mx-auto ">
            <p class="text-md md:text-lg font-sans px-4 pt-2 mb-4">{product.description}</p>

            <div class="flex justify-between mx-auto text-center py-2 pr-8 pl-2">
                <h5 class="text-lg font-semibold mx-2 py-2">Select number of items: </h5>
                <div class="flex justify-center">
                    <button 
                        class="rounded-full bg-gray-700 w-10 h-10 text-2xl text-center text-white px-1 items-center"
                        on:click={decreaseQuantity}
                    >
                        -
                    </button>
                
                    <h1 class="w-10  pt-2">{selectedQuantity}</h1>

                    <button
                        class="rounded-full bg-gray-700 w-10 h-10 text-2xl text-center text-white px-1"
                        on:click={increaseQuantity}
                    >
                        +
                    </button>
                </div>
            </div>
            
            {#if product.quantity <= 0}
                <h6 class="text-red-600 font-semibold text-md mx-4">Out of stock</h6>
            {:else if product.quantity > 0 && product.quantity <= 5}
                <h6 class="text-gray-500 font-semibold text-md mx-4">{product.quantity} products left</h6>
            {:else}
                <h6 class="text-gray-500 font-semibold text-md mx-4">In stock</h6>
            {/if}

            

            <div class="mx-4 text-lg font-sans">
                {#if product.discount > 0}
                        <div class="block">
                            <span class="block line-through">{product.price}</span> 
                            <span class="text-red-500">{parseFloat((product.price - product.price / 100 * product.discount).toFixed(2))}$</span>
                        </div>
                        
                    {:else}
                        <span class="text-red-500">{product.price}$</span>
            {/if}
            </div>

            <div class="flex justify-center text-center items-center mx-auto my-4">
                <button 
                    disabled={product.quantity > 0 ? false : true}
                    class={`w-full ${product.quantity <= 0 ? 'bg-blue-400' : 'bg-blue-500 hover:bg-blue-600'}  rounded-md text-white text-lg font-semibold  py-2 mx-4`}
                >
                    Add to cart
                </button>
            </div>

            <div class="w-full">
                <div class="flex justify-center text-center text-white pb-4">
                    <button 
                        class="w-3/6 rounded-md bg-red-600 hover:bg-red-700 hover:text-gray-300 p-2 ml-4"
                        on:click={openDeleteDialog}
                    >
                        Delete product
                    </button>

                    <button
                        on:click={openUpdateDialog}
                        class="w-3/6 rounded-md bg-green-600 hover:bg-green-700 hover:text-gray-300 p-2 mx-4"
                        
                    >
                        Edit product
                    </button>
                </div>
            </div>
        </div>
    </div>



    <div class="container p-4 bg-gray-50 mx-6">
        {#if similarProducts.length > 0}
            <h1 class="text-2xl font-bold mb-4">Similar products: </h1>
        {/if}
        <div class="mx-auto grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {#each similarProducts as product}
                <ProductCard {product} />
            {/each}
        </div>
    </div>
</div>

<ProductDelete {showDeleteDialog} {productId} />

<UpdateProductDialog {showUpdateDialog} {product} />