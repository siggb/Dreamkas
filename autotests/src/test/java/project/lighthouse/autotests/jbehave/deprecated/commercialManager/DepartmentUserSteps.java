package project.lighthouse.autotests.jbehave.deprecated.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.objects.api.Department;
import project.lighthouse.autotests.steps.AuthorizationSteps;
import project.lighthouse.autotests.steps.api.commercialManager.DepartmentApiSteps;
import project.lighthouse.autotests.steps.deprecated.commercialManager.DepartmentSteps;

import java.io.IOException;

public class DepartmentUserSteps {
    ExamplesTable departmentData;

    @Steps
    DepartmentSteps formSteps;
    @Steps
    AuthorizationSteps authorizationSteps;

    @Steps
    DepartmentApiSteps departmentApiSteps;

    @When("user clicks create new department button")
    public void userClicksCreateNewDepartmentButton() {
        formSteps.clickCreateNewDepartmentButton();
    }

    @When("user clicks department form submit button")
    public void userClicksFormSubmitButton() {
        formSteps.clickCreateDepartmentSubmitButton();
    }

    @When("user fills department form with following data $formData")
    public void userFillsFormData(ExamplesTable formData) {
        formSteps.fillStoreFormData(formData);
        departmentData = formData;
    }

    @Then("user checks department card data")
    public void userChecksDepartmentCardData() {
        formSteps.checkDepartmentDataInList(departmentData);
    }

    @Then("user checks department card data $departmentData")
    public void userChecksDepartmentCardData(ExamplesTable departmentData) {
        formSteps.checkDepartmentDataInCard(departmentData);
    }

    @When("user clicks to department '$departmentNumber'")
    public void userClickDepartmentRow(String departmentNumber) {

    }

    @Given("created default store with department '$departmentNumber', '$departmentName'")
    public void createdDefaultStoreWithDepartment(String departmentNumber, String departmentName) throws IOException, JSONException {
        Department department = departmentApiSteps.createStoreDepartmentThroughPost(departmentNumber, departmentName);
        formSteps.navigateToDepartmentPage(department.getId(), department.getStoreID());
        authorizationSteps.authorization("commercialManager");

    }

    @When("user clicks edit department link")
    public void userClicksEditDepartmentLink() {
        formSteps.clicksEditDepartmentLink();
    }

    @Given("there is created department and user starts to edit it and fills form with $fieldsData")
    public void thereIsCreatedDepartmentAndUserStartsToEditItAndFillsForm(ExamplesTable fieldsData) throws IOException, JSONException {
        Department department = departmentApiSteps.createStoreDepartmentThroughPost(Department.DEFAULT_NUMBER, Department.DEFAULT_NAME);
        formSteps.navigateToDepartmentPage(department.getId(), department.getStoreID());
        authorizationSteps.authorization("commercialManager");
        formSteps.clicksEditDepartmentLink();
        formSteps.fillStoreFormData(fieldsData);
        formSteps.clickCreateDepartmentSubmitButton();
    }
}