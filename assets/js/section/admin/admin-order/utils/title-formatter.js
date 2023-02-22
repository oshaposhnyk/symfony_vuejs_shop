export function getProductInformativeTitle(product) {
    return "#"
        + product.id + " "
        + product.title + " / P: $"
        + product.price + " / q: "
        + product.quantity
        ;
}