export class Base {

    loadFields(itemData?) {
        if (itemData !== undefined) {
            for (const index in itemData) {
                if (index !== undefined) {
                    this[index] = itemData[index];
                }
            }
        }
    }
}
