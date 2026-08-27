import { initCompanyForm } from './company/form';
import { initCompanyIndex } from './company/index';
import { initCompanyShow } from './company/show';
// import { initCompanyFilters } from './company/filters';

export default function initCompany() {
    initCompanyForm();
    initCompanyIndex();
    initCompanyShow();
    // initCompanyFilters();

}