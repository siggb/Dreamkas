package project.lighthouse.autotests.api;

import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.*;

import java.io.IOException;

public class ApiConnect {

    private final HttpExecutor httpExecutor;

    public ApiConnect(String userName, String password) throws IOException, JSONException {
        httpExecutor = new HttpExecutor(userName, password);
    }

    public Department createStoreDepartmentThroughPost(Department department) throws JSONException, IOException {
        if (!StaticData.departments.containsKey(department.getNumber())) {
            httpExecutor.executePostRequest(department);
            StaticData.departments.put(department.getNumber(), department);
            return department;
        } else {
            return StaticData.departments.get(department.getNumber());
        }
    }

    public Product createProductThroughPost(Product product, SubCategory subCategory) throws JSONException, IOException {
        if (!subCategory.hasProduct(product)) {
            getSubCategoryMarkUp(subCategory);
            httpExecutor.executePostRequest(product);
            subCategory.addProduct(product);
            StaticData.products.put(product.getSku(), product);
            return product;
        } else {
            return subCategory.getProduct(product);
        }
    }

    public void getSubCategoryMarkUp(SubCategory subCategory) throws IOException, JSONException {
        String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/subcategories"), subCategory.getId());
        String response = httpExecutor.executeSimpleGetRequest(apiUrl, true);
        JSONObject jsonObject = new JSONObject(response);
        StaticData.retailMarkupMax = (!jsonObject.isNull("retailMarkupMax"))
                ? jsonObject.getString("retailMarkupMax")
                : null;
        StaticData.retailMarkupMin = (!jsonObject.isNull("retailMarkupMin"))
                ? jsonObject.getString("retailMarkupMin")
                : null;
    }

    public String getProductPageUrl(String productSku) throws JSONException {
        String productId = StaticData.products.get(productSku).getId();
        return String.format("%s/products/%s", UrlHelper.getWebFrontUrl(), productId);
    }

    public Invoice createInvoiceThroughPost(Invoice invoice) throws JSONException, IOException {
        if (!StaticData.invoices.containsKey(invoice.getSku())) {
            httpExecutor.executePostRequest(invoice);
            StaticData.invoices.put(invoice.getSku(), invoice);
            return invoice;
        } else {
            return StaticData.invoices.get(invoice.getSku());
        }
    }

    public String getInvoicePageUrl(String invoiceName) throws JSONException {
        Invoice invoice = StaticData.invoices.get(invoiceName);
        return String.format("%s/invoices/%s?editMode=true", UrlHelper.getWebFrontUrl(), invoice.getId());
    }

    public void addProductToInvoice(String invoiceName, String productSku, String quantity, String price)
            throws JSONException, IOException {
        Product product = StaticData.products.get(productSku);
        Invoice invoice = StaticData.invoices.get(invoiceName);
        // TODO: Нужно проверять найдены ли такой товар и такая накладная
        if (!hasInvoiceProduct(invoice, product)) {
            String apiUrl = String.format("%s%s/%s/products.json", UrlHelper.getApiUrl(""), invoice.getApiUrl(), invoice.getId());
            String productJsonData = InvoiceProduct.getJsonObject(product.getId(), quantity, price).toString();
            httpExecutor.executePostRequest(apiUrl, productJsonData);
        }
    }

    private Boolean hasInvoiceProduct(Invoice invoice, Product product) throws IOException, JSONException {
        JSONArray jsonArray = getInvoiceProducts(invoice);
        for (int i = 0; i < jsonArray.length(); i++) {
            if (jsonArray.getJSONObject(i).getJSONObject("product").getString("id").equals(product.getId())) {
                return true;
            }
        }
        return false;
    }

    private JSONArray getInvoiceProducts(Invoice invoice) throws IOException, JSONException {
        String url = String.format("%s%s/%s/products", UrlHelper.getApiUrl(""), invoice.getApiUrl(), invoice.getId());
        String response = httpExecutor.executeSimpleGetRequest(url, true);
        return new JSONArray(response);
    }

    public WriteOff createWriteOffThroughPost(WriteOff writeOff) throws JSONException, IOException {
        if (!StaticData.writeOffs.containsKey(writeOff.getNumber())) {
            httpExecutor.executePostRequest(writeOff);
            StaticData.writeOffs.put(writeOff.getNumber(), writeOff);
            return writeOff;
        } else {
            return StaticData.writeOffs.get(writeOff.getNumber());
        }
    }

    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws JSONException, IOException {
        Product product = StaticData.products.get(productSku);
        WriteOff writeOff = StaticData.writeOffs.get(writeOffNumber);
        String apiUrl = String.format("%s%s/%s/products.json", UrlHelper.getApiUrl(""), writeOff.getApiUrl(), writeOff.getId());

        String productJsonData = WriteOffProduct.getJsonObject(product.getId(), quantity, price, cause).toString();
        httpExecutor.executePostRequest(apiUrl, productJsonData);
    }

    public String getWriteOffPageUrl(String writeOffNumber) throws JSONException {
        String writeOffId = StaticData.writeOffs.get(writeOffNumber).getId();
        return String.format("%s/writeOffs/%s", UrlHelper.getWebFrontUrl(), writeOffId);
    }

    public Group createGroupThroughPost(Group group) throws JSONException, IOException {
        if (!StaticData.isGroupCreated(group.getName())) {
            httpExecutor.executePostRequest(group);
            StaticData.groups.put(group.getName(), group);
            return group;
        } else {
            return getUpdatedGroup(group);
        }
    }

    private Group getUpdatedGroup(Group group) throws IOException, JSONException {
        group = StaticData.groups.get(group.getName());
        return updatedGroup(group);
    }

    private Group updatedGroup(Group group) throws JSONException, IOException {
        String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl(group.getApiUrl()), group.getId());
        Group updatedGroup = new Group(new JSONObject(httpExecutor.executeSimpleGetRequest(apiUrl, true)));
        StaticData.groups.put(group.getName(), updatedGroup);
        return updatedGroup;
    }

    public Category createCategoryThroughPost(Category category, Group group) throws IOException, JSONException {
        if (!(group.hasCategory(category))) {
            createGroupThroughPost(group);
            httpExecutor.executePostRequest(category);
            StaticData.categories.put(category.getName(), category);
            return category;
        } else {
            Category updatedCategory = updatedGroup(group).getCategory(category);
            StaticData.categories.put(category.getName(), updatedCategory);
            return updatedCategory;
        }
    }

    public String getGroupPageUrl(String groupName) throws JSONException {
        String groupId = StaticData.groups.get(groupName).getId();
        return String.format("%s/catalog/%s", UrlHelper.getWebFrontUrl(), groupId);
    }

    public String getCategoryPageUrl(String categoryName, String groupName) throws JSONException {
        String groupPageUrl = getGroupPageUrl(groupName) + "/%s";
        String categoryId = StaticData.categories.get(categoryName).getId();
        return String.format(groupPageUrl, categoryId);
    }

    public SubCategory createSubCategoryThroughPost(SubCategory subCategory, Category category, Group group) throws JSONException, IOException {
        if (!category.hasSubCategory(subCategory)) {
            group = createGroupThroughPost(group);
            createCategoryThroughPost(category, group);
            httpExecutor.executePostRequest(subCategory);
            StaticData.subCategories.put(subCategory.getName(), subCategory);
            return subCategory;
        } else {
            return StaticData.subCategories.get(subCategory.getName());
        }
    }

    public String getSubCategoryProductListPageUrl(String subCategoryName, String categoryName, String groupName) throws JSONException {
        String categoryPageUrl = getCategoryPageUrl(categoryName, groupName);
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return categoryPageUrl + "/" + subCategoryId;
    }

    public String getSubCategoryProductCreatePageUrl(String subCategoryName) throws JSONException {
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return String.format("%s/products/create?subCategory=%s", UrlHelper.getWebFrontUrl(), subCategoryId);
    }

    private User createUserThroughPost(User user) throws JSONException, IOException {
        if (!StaticData.users.containsKey(user.getUserName())) {
            httpExecutor.executePostRequest(user);
            StaticData.users.put(user.getUserName(), user);
            return user;
        } else {
            return StaticData.users.get(user.getUserName());
        }
    }

    public User createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        User user = new User(name, position, login, password, role);
        return createUserThroughPost(user);
    }

    public User getUser(String userName) throws IOException, JSONException {
        JSONArray jsonArray = new JSONArray(httpExecutor.executeSimpleGetRequest(UrlHelper.getApiUrl("/users"), true));
        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject jsonObject = jsonArray.getJSONObject(i);
            if (jsonObject.getString("username").equals(userName)) {
                User user = new User(new JSONObject());
                user.setJsonObject(jsonObject);
                StaticData.users.put(userName, user);
                return user;
            }
        }
        return null;
    }

    public Store createStoreThroughPost(Store store) throws JSONException, IOException {
        if (!StaticData.stores.containsKey(store.getNumber())) {
            httpExecutor.executePostRequest(store);
            StaticData.stores.put(store.getNumber(), store);
            return store;
        } else {
            return StaticData.stores.get(store.getNumber());
        }
    }

    public String getStoreId(String storeNumber) throws JSONException {
        return StaticData.stores.get(storeNumber).getId();
    }

    public String getUserPageUrl(String userName) throws JSONException {
        String userId = StaticData.users.get(userName).getId();
        return String.format("%s/users/%s", UrlHelper.getWebFrontUrl(), userId);
    }

    public void setSubCategoryMarkUp(String retailMarkupMax, String retailMarkupMin, SubCategory subCategory) throws JSONException, IOException {
        String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/subcategories"), subCategory.getId());
        httpExecutor.executePutRequest(apiUrl, new JSONObject()
                .put("category", subCategory.getCategory().getId())
                .put("name", subCategory.getName())
                .put("retailMarkupMax", retailMarkupMax)
                .put("retailMarkupMin", retailMarkupMin)
        );
    }

    public void promoteUserToManager(Store store, User user, String type) throws JSONException, IOException {
        if (!hasStoreManager(store, user, type)) {
            String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/stores"), store.getId());
            String data = "_method=LINK";
            HttpPost httpPost = httpExecutor.getHttpPost(apiUrl);
            httpPost.setHeader("Link", getLinkHeaderValue(user, type));
            StringEntity entity = new StringEntity(data, "UTF-8");
            entity.setContentType("application/x-www-form-urlencoded; charset=UTF-8");
            httpPost.setEntity(entity);
            httpExecutor.executeHttpMethod(httpPost);
        }
    }


    private String getLinkHeaderValue(User user, String type) throws JSONException {
        return String.format("<%s/%s>; rel=\"%s\"", UrlHelper.getApiUrl("/users"), user.getId(), type);
    }

    private Boolean hasStoreManager(Store store, User user, String type) throws JSONException, IOException {
        String apiUrl = String.format("%s/%s/%s", UrlHelper.getApiUrl("/stores"), store.getId(), type);
        String response = httpExecutor.executeSimpleGetRequest(apiUrl, true);
        JSONArray jsonArray = new JSONArray(response);
        for (int i = 0; i < jsonArray.length(); i++) {
            if (jsonArray.getJSONObject(i).getString("id").equals(user.getId())) {
                return true;
            }
        }
        return false;
    }

    public String setSet10ImportUrl(String value) throws IOException, JSONException {
        JSONObject jsonObject = new JSONObject(httpExecutor.executeSimpleGetRequest(UrlHelper.getApiUrl("/configs/by/name?query=set10-import-url"), true));
        String targetUrl = UrlHelper.getApiUrl("/configs/" + jsonObject.getString("id"));
        return httpExecutor.executePutRequest(targetUrl, new JSONObject()
                .put("name", "set10-import-url")
                .put("value", value));
    }
}