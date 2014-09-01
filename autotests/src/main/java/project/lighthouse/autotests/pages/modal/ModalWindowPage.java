package project.lighthouse.autotests.pages.modal;

import net.thucydides.core.annotations.WhenPageOpens;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.common.ModalWindowPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Common page object representing modal window
 */
public abstract class ModalWindowPage extends CommonPageObject implements ModalWindowPageObject {

    public ModalWindowPage(WebDriver driver) {
        super(driver);
    }

    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal-title']")).getText();
    }

    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }

    @WhenPageOpens
    public void whenPageOpens() {
        //Check that modal window is open
        findVisibleElement(By.id("modal-group"));
    }

    public void closeIconClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'close')]")).click();
    }

    @Override
    public void deleteButtonClick() {
        throw new AssertionError("This modal window does not have delete button");
    }

    @Override
    public void confirmDeleteButtonClick() {
        throw new AssertionError("This modal window does not have delete button");
    }
}
