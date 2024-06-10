<script lang="ts">
    import type Product from '../interfaces/products'
    import {addItemToShoppingCart} from '../api/carts'
    import {goto} from '$app/navigation'

    const handleNagivation = (name: string | undefined) => {
        if (name !== undefined)
            goto(`/products/${name}`);
    }

    const handleKey = (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            
        }
    }

    const handleAddProduct = async () => {
        try {
            const response = await addItemToShoppingCart('test', product.id.toString(), 1);

            if (response) {
                const username = 'text';
                console.log("Product added successfully");
                goto(`/cart/${username}`);
            }
        } catch (error) {
            console.error(error);
        }
    }

    export let product: Product;
</script>

<div 
    class="block w-full bg-white rounded-lg shadow-xl cursor-pointer p-2 my-2"
    on:click={() => handleNagivation(product.name)}
    on:keydown={handleKey}
    role="button"
    tabindex=0
>
        <img 
            src={product.photoUrl} 
            alt="product"
            class="p-2"
            width="400vh"
            height="100vh"
        >

        <div class="w-full font-semibold p-2">
            <h1>{product.name}</h1>

            <p class="my-4 font-sans text-sm">{product.description}</p>

            <div class="flex justify-between my-4 ">
                <div class="block">
                    {#if product.discount > 0}
                        <span class="block">
                            <span class="line-through">{product.price}</span> 
                            <span class="mx-2 bg-red-500 rounded-md px-2 py-1 text-white">
                                {product.discount}%
                            </span>
                        </span>
                        <span class="text-red-500">{parseFloat((product.price - product.price / 100 * product.discount).toFixed(2))}$</span>
                    {:else}
                        <span class="text-red-500">{product.price}$</span>
                    {/if}
                </div>
                <button
                    on:click={handleAddProduct} 
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-md text-lg px-3 py-1"
                >
                    +
                </button>
            </div>
        </div>
</div>