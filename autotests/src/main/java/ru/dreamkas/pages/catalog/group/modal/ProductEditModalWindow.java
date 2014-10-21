package ru.dreamkas.pages.catalog.group.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Edit product modal window
 */
public class ProductEditModalWindow extends ProductCreateModalWindow {

    public ProductEditModalWindow(WebDriver driver) {
        super(driver);
    }

    protected WebElement findDeleteButton() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink']"));
    }

    public void deleteButtonClick() {
        findDeleteButton().click();
    }

    public String getDeleteButtonText() {
        return findDeleteButton().getText();
    }

    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink']")).click();
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }

}
