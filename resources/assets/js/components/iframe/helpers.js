import lodash from 'lodash';

export default {
  updatePrint (prints, searchKey, searchValue, dataKey, dataValue) {
    return _.reduce(prints, (carry, item) => {
      if (item[searchKey] == searchValue) {
        item[dataKey] = dataValue;
      }
      carry.push(item);
      return carry;
    }, []);
  },
  filterAreasByApplicationType (areas, applicationType) {
    if (_.isEmpty(applicationType)) {
      return areas;
    }
    return _.filter(areas, ['application_type_id', applicationType.id])
  },
  // Getters for print
  getPrint (prints, printId) {
    const print = _.find(prints, ['id', printId]);
    return print;
  },
  getPrintSelectedApplicationType (prints, printId) {
    const print = _.find(prints, ['id', printId]);
    return print.selectedApplicationType;
  },
  getPrintSelectedColor (prints, printId) {
    const print = _.find(prints, ['id', printId]);
    return print.selectedColor;
  },
  getPrintSelectedCopy (prints, printId) {
    const print = _.find(prints, ['id', printId]);
    return print.selectedCopy;
  },
  getPrintAreas (prints, printId) {
    const print = _.find(prints, ['id', printId]);
    return print.areas;
  },
  getPrintColors (prints, printId) {
    const print =_.find(prints, ['id', printId]);
    return _.range(1, print.selectedArea.max_colors + 1);
  },
  getPrintCopies (prints, printId) {
    const print = _.find(prints, ['id', printId]);
    return _.range(1, print.selectedArea.max_copy + 1);
  },
  getPrintIsClose (prints, printId) {
    const print = _.find(prints, ['id', printId]);
    return print.isClose;
  },
  // Getters for applicationTypes
  getApplicationType: (applicationTypes, id) => {
    const applicationType = _.find(applicationTypes, ['id', id]);
    return applicationType;
  },
  // Getters for areas
  getArea: (areas, areaId) => {
    const area = _.find(areas, ['id', areaId]);
    return area;
  },
};
