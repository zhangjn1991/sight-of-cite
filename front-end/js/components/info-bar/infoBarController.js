// Generated by CoffeeScript 1.7.1
angular.module('sightApp').controller("InfoBarController", function($scope) {
  $scope.globalCtrl.infobarCtrl = this;
  this.entity = {};
  this.isEditing = false;
  this.tempEntityDetail = null;
  this.startEdit = function() {
    this.tempEntityDetail = this.tempEntityDetail || {};
    this.overwriteObject(this.entity, this.tempEntityDetail);
    return this.isEditing = true;
  };
  this.saveEdit = function() {
    this.overwriteObject(this.tempEntityDetail, this.entity);
    return this.isEditing = false;
  };
  this.cancelEdit = function() {
    this.tempEntityDetail = null;
    return this.isEditing = false;
  };
  this.setCurrentEntity = function(entity) {
    this.entity = entity;
    if (this.isNewEntity(entity)) {
      return this.startEdit();
    }
  };
  this.removeCurrentEntity = function() {
    return this.entity = {};
  };
  this.overwriteObject = function(fromObj, toObj) {
    var k, v, _results;
    _results = [];
    for (k in fromObj) {
      v = fromObj[k];
      _results.push(toObj[k] = v);
    }
    return _results;
  };
  this.isNewEntity = function(entity) {
    return _.isEmpty(entity);
  };
  return 0;
});
