package project.lighthouse.autotests.objects.web.catalog;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

/**
 * Collection to store groups
 */
public class GroupObjectCollection extends AbstractObjectCollection {

    public GroupObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public GroupObject createNode(WebElement element) {
        return new GroupObject(element);
    }
}