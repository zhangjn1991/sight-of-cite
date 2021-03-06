// Generated by CoffeeScript 1.7.1
angular.module('sightApp').controller("InfoBarController", function($scope) {
  var self;
  self = this;
  $scope.globalCtrl.infoBarCtrl = this;
  this.tabIndex = 0;
  this.entity = {};
  this.isEditing = false;
  this.tempEntityDetail = null;
  this.currentCitationList = [];
  this.citationEntity = {};
  this.isReference = true;
  this.startEdit = function() {
    this.tempEntityDetail = this.tempEntityDetail || {};
    this.overwriteObject(this.entity, this.tempEntityDetail);
    return this.isEditing = true;
  };
  this.saveEdit = function() {
    var actionName, isNewPaper, tagsToAdd;
    isNewPaper = this.isNewEntity(this.entity);
    tagsToAdd = _.difference(this.tempEntityDetail.tags, this.entity.tags);
    this.overwriteObject(this.tempEntityDetail, this.entity);
    this.isEditing = false;
    actionName = isNewPaper ? "add_paper" : "update_paper";
    console.log("AJAX: " + actionName);
    $.post($scope.globalCtrl.getServerAddr(), {
      action: actionName,
      data: this.entity
    }, function(res) {
      console.log(res);
      if (isNewPaper) {
        return self.entity.pub_id = res.pub_id;
      }
    }, 'json');
    return _.each(tagsToAdd, function(d) {
      return $.post($scope.globalCtrl.getServerAddr(), {
        action: 'add_tag_by_paper_id',
        data: {
          pub_id: self.entity.pub_id,
          tag_content: d
        }
      });
    });
  };
  this.cancelEdit = function() {
    this.tempEntityDetail = null;
    return this.isEditing = false;
  };
  this.setCurrentEntity = function(entity) {
    this.entity = entity;
    this.entity.tags = _.filter(this.entity.tags, function(d) {
      return d.length > 0;
    });
    if (this.isNewEntity(entity)) {
      return this.startEdit();
    } else {
      return this.setCitationList(entity);
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
    return !((entity != null) && (entity.pub_id != null));
  };
  this.setTabIndex = function(index) {
    return this.tabIndex = index;
  };
  this.setCitationList = function(entity) {
    if ((entity != null)) {
      this.currentCitationList = this.isReference ? entity.references : entity.citedbys;
      return this.currentCitationList = _.flatten(this.currentCitationList);
    }
  };
  this.setCitationEntity = function(entity) {
    return this.citationEntity = entity;
  };
  this.setIsReference = function(isReference) {
    this.isReference = isReference;
    return this.setCitationList(this.entity);
  };
  this.saveCitationNote = function() {
    var data;
    data = {
      pub_id_1: this.entity.pub_id,
      pub_id_2: this.citationEntity.pub_id,
      note_content: this.citationEntity.note_content,
      rating: this.citationEntity.note_rating,
      date: null
    };
    return $.post($scope.globalCtrl.getServerAddr(), {
      action: 'add_note_by_paper_ids',
      data: data
    }, function(res) {
      return console.log(res);
    });
  };
  this.isInvalidCitation = function(citation) {
    return citation.pub_id === this.entity.pub_id;
  };
  return 0;
});
