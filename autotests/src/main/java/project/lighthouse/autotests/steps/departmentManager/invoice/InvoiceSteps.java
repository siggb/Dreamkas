package project.lighthouse.autotests.steps.departmentManager.invoice;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.By;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.items.DateTime;
import project.lighthouse.autotests.elements.preLoader.ProductEditionPreLoader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.helper.exampleTable.invoice.InvoiceExampleTableUpdater;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.web.invoice.InvoiceProductObject;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoicePage;
import project.lighthouse.autotests.pages.departmentManager.invoice.deprecated.InvoiceSearchPage;
import project.lighthouse.autotests.storage.Storage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class InvoiceSteps extends ScenarioSteps {

    InvoicePage invoicePage;
    InvoiceSearchPage invoiceSearchPage;

    private ExamplesTable examplesTable;

    @Step
    public void assertFieldLabel(String elementName) {
        invoicePage.checkFieldLabel(elementName);
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        invoicePage.fieldInput(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void input(String elementName, String value) {
        invoicePage.input(elementName, value);
    }

    @Step
    public void checkValues() {
        invoicePage.checkValues(examplesTable);
    }

    @Step
    public void checkValues(ExamplesTable examplesTable) throws JSONException {
        ExamplesTable updatedExamplesTable = new InvoiceExampleTableUpdater(examplesTable).updateValues();
        invoicePage.checkValues(updatedExamplesTable);
    }

    @Step
    public InvoiceProductObject getInvoiceProductObject(String locator) {
        return (InvoiceProductObject) invoicePage.getInvoiceProductsCollection().getAbstractObjectByLocator(locator);
    }

    @Step
    public void invoiceProductObjectQuantityType(String locator, String value) {
        getInvoiceProductObject(locator).quantityType(value);
    }

    @Step
    public void lastCreatedProductObjectQuantityType(String value) throws JSONException {
        invoiceProductObjectQuantityType(
                Storage.getInvoiceVariableStorage().getProduct().getName(),
                value);
    }

    @Step
    public void assertInvoiceProductObjectQuantity(String locator, String expectedQuantity) {
        assertThat(getInvoiceProductObject(locator).getQuantity(), is(expectedQuantity));
    }

    @Step
    public void invoiceProductObjectPriceType(String locator, String value) {
        getInvoiceProductObject(locator).priceType(value);
    }

    @Step
    public void invoiceProductEditionPreLoaderWait() {
        new ProductEditionPreLoader(getDriver()).await();
    }

    @Step
    public void invoiceProductsCollectionExactCompare(ExamplesTable examplesTable) throws JSONException {
        ExamplesTable updatedExamplesTable = new InvoiceExampleTableUpdater(examplesTable).updateValues();
        invoicePage.getInvoiceProductsCollection().exactCompareExampleTable(updatedExamplesTable);
    }

    @Step
    public void assertInvoiceTotalSum(String expected) {
        assertThat(invoicePage.getTotalSum(), is(expected));
    }

    @Step
    public void assertInvoiceVatSum(String expected) {
        assertThat(invoicePage.getVatSum(), is(expected));
    }

    @Step
    public void acceptProductsButtonClick() {
        invoicePage.acceptProductsButtonClick();
    }

    @Step
    public void cancelLinkClick() {
        invoicePage.cancelLinkClick();
    }

    @Step
    public void openStoreInvoiceCreatePage(Store store) throws JSONException {
        String url = String.format(
                "%s/stores/%s/invoices/create",
                UrlHelper.getWebFrontUrl(),
                store.getId());
        getDriver().navigate().to(url);
    }

    @Step
    public void assertAcceptanceDateFieldContainsNowDate() {
        String nowDate = DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN);
        invoicePage.checkValue("acceptanceDate", nowDate);
    }

    @Step
    public void inputGeneratedData(String elementName, int charNumber) {
        String generatedData = new StringGenerator(charNumber).generateTestData();
        input(elementName, generatedData);
    }

    @Step
    public void assertFieldLength(String elementName, int fieldLength) {
        invoicePage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void assertInvoiceNumber(String expectedNumber) {
        assertThat(invoicePage.getInvoiceNumber(), is(expectedNumber));
    }

    @Step
    public void invoiceListSearchObjectClick(String locator) {
        invoiceSearchPage.getInvoiceListSearchObjectCollection().clickByLocator(locator);
    }

    @Step
    public void lastCreatedInvoiceListSearchObjectClick() {
        invoiceListSearchObjectClick(Storage.getInvoiceVariableStorage().getNumber());
    }

    @Step
    public void searchInvoiceByLastCreatedInvoiceNumber() {
        invoiceSearchPage.input("skuOrSupplierInvoiceSku", Storage.getInvoiceVariableStorage().getNumber());
    }

    @Step
    public void assertAutoCompletePlaceHolder(String expectedPlaceHolder) {
        assertThat(
                invoicePage.getItemAttribute("invoice product autocomplete", "placeholder"),
                is(expectedPlaceHolder));
    }

    @Step
    public void downloadAgreementButtonShouldBeVisible() {
        invoicePage.getDownloadAgreementFileButton().shouldBeVisible();
    }

    @Step
    public void downloadAgreementButtonShouldBeNotVisible() {
        invoicePage.getDownloadAgreementFileButton().shouldBeNotVisible();
    }

    @Step
    public void invoiceFocusOutClick() {
        invoicePage.findVisibleElement(By.className("form__totalSum")).click();
    }

    @Step
    public void typeInToActiveWebElement(String value) {
        invoicePage.$(getDriver().switchTo().activeElement()).type(value);
    }

    @Step
    public void assertActiveElementIsAutoComplete() {
        assertThat(
                getDriver().switchTo().activeElement(),
                is(invoicePage.getItems().get("invoice product autocomplete").getWebElement())
        );
    }

    @Step
    public void invoiceProductObjectClick(String locator) {
        invoicePage.getInvoiceProductsCollection().clickByLocator(locator);
    }

    @Step
    public void lastCreatedInvoiceProductObjectClick() throws JSONException {
        invoiceProductObjectClick(
                Storage.getInvoiceVariableStorage().getProduct().getName());
    }

    @Step
    public void invoiceProductObjectDeleteIconClick(String locator) {
        getInvoiceProductObject(locator).deleteIconClick();
    }

    @Step
    public void lastAddedInvoiceProductObjectDeleteIconClick() throws JSONException {
        invoiceProductObjectDeleteIconClick(
                Storage.getInvoiceVariableStorage().getProduct().getName());
    }

    @Step
    public void collectionDoNotContainInvoiceProductObjectByLocator(String locator) {
        try {
            invoicePage.getInvoiceProductsCollection().notContains(locator);
        } catch (TimeoutException ignored) {
        }
    }

    @Step
    public void collectionDoNotContainlastAddedInvoiceProductObject() throws JSONException {
        collectionDoNotContainInvoiceProductObjectByLocator(
                Storage.getInvoiceVariableStorage().getProduct().getName());
    }
}
