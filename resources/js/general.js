import { initIndustryIndex } from "./general/industry";
import { initCountryIndex } from "./general/country";
import { initCityIndex } from "./general/city";

export default function initGenerals() {
    initIndustryIndex();
    initCountryIndex();
    initCityIndex();
}